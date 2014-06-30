<?php
namespace Toy\Orm\Db;

class Database
{

    protected $connection = NULL;
    protected $settings = null;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function __destruct()
    {
        $this->connection = NULL;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    protected function log($sql, $arguments)
    {
        $content = $sql;
        foreach ($arguments as $n => $v) {
            $content .= '[' . $n . ':' . $v . ']';
        }
        Configuration::$logger->v($content, 'sql');
    }

    public function isConnected()
    {
        return $this->connection ? TRUE : false;
    }

    public function inTransaction()
    {
        return $this->connection->inTransaction();
    }

    public function escape($value)
    {
        return $this->connection->quote($value);
    }

    public function open()
    {
        if (!$this->connection) {
            $this->connection = new \PDO($this->settings['dsn']);
        }
        return $this;
    }

    public function close()
    {
        if ($this->connection) {
            $this->connection = NULL;
        }
    }

    public function begin()
    {
        if (!$this->inTransaction()) {
            if (!$this->connection->beginTransaction()) {
                $this->handleError($this->connection->errorInfo());
            }
        }
        return $this;
    }

    public function commit()
    {
        if ($this->inTransaction()) {
            if (!$this->connection->commit()) {
                $this->handleError($this->connection->errorInfo());
            }
        }
        return $this;
    }

    public function rollback()
    {
        if ($this->inTransaction()) {
            if (!$this->connection->rollback()) {
                $this->handleError($this->connection->errorInfo());
            }
        }
        return $this;
    }

    public function getLastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    public function execute($sql, $parameters = array())
    {
        $this->log($sql, $parameters);
        $statement = $this->connection->prepare(str_replace('{t}', Configuration::$tablePrefix, $sql));
        if ($statement === false) {
            $this->handleError($this->connection->errorInfo());
        }
        foreach ($parameters as $name => $value) {
            $statement->bindValue($name, $value);
        }
        $result = $statement->execute();
        $statement->closeCursor();
        $this->handleError($statement->errorInfo());
        return $result;
    }

    public function fetch($sql, $parameters = array())
    {
        $this->log($sql, $parameters);
        $statement = $this->connection->prepare(str_replace('{t}', Configuration::$tablePrefix, $sql));
        if ($statement === false) {
            $this->handleError($this->connection->errorInfo());
        }
        foreach ($parameters as $name => $value) {
            $statement->bindValue($name, $value);
        }
        $statement->execute();
        $this->handleError($statement->errorInfo());
        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return new Result($rows);
    }

    public function insert($statement)
    {
        $fa = array();
        $va = array();
        $params = array();
        foreach ($statement->getValues() as $n => $v) {
            $fa[] = $n;
            $va[] = ':p' . count($params);
            $params[':p' . count($params)] = $v;
        }
        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $statement->getTable(), implode(',', $fa), implode(',', $va));
        return $this->execute($sql, $params);
    }

    public function update($statement)
    {
        $fv = array();
        $where = '';
        $params = array();
        foreach ($statement->getValues() as $n => $v) {
            $fv[] = $n . '=:p' . count($params);
            $params[':p' . count($params)] = $v;
        }
        if (count($statement->getConditions()) > 0) {
            $where = $this->parseWhere($statement->getConditions(), $params);
        }
        $sql = sprintf('UPDATE %s SET %s %s', $statement->getTable(), implode(',', $fv), $where);
        return $this->execute(trim($sql), $params);
    }

    public function delete($statement)
    {
        $where = '';
        $params = array();
        if (count($statement->getConditions()) > 0) {
            $where = $this->parseWhere($statement->getConditions(), $params);
        }
        $sql = sprintf('DELETE FROM %s %s', $statement->getTable(), $where);
        return $this->execute(trim($sql), $params);
    }

    public function select($statement)
    {
        $params = array();
        if (count($statement->getFields()) > 0) {
            $sql = 'SELECT ' . implode(',', ArrayUtil::toArray($query->getFields(), function ($item, $index) {
                    return array($this->parseFunction($item), null);
                }));
        } else {
            $sql = 'SELECT *';
        }
        $sql .= ' FROM ' . $statement->getTable();

        $joins = $statement->getJoins();
        foreach ($joins as $v) {
            $sql .= ' ' . strtoupper($v[0]) . ' JOIN ' . $v[1] . ' ON ' . $v[2] . '=' . $v[3];
        }

        if (count($statement->getConditions()) > 0) {
            $sql .= ' ' . $this->parseWhere($query->getConditions(), $params);
        }

        if (count($statement->getOrderBy()) > 0) {
            $arr = array();
            foreach ($statement->getOrderBy() as $f => $d) {
                $arr[] = $f . ' ' . $d;
            }
            $sql .= ' ORDER BY ' . implode(',', $arr);
        }

        $offset = $statement->getOffset();
        $limit = $statement->getLimit();
        if ($offset + $limit > 0) {
            $sql .= ' LIMIT ' . $offset . ',' . $limit;
        }

        return $this->fetch($sql, $params);
    }

    private function parseWhere($conditions, array &$params)
    {
        $result = array();
        foreach ($conditions as $v) {
            if (is_string($v)) {
                $result[] = $v;
            } elseif (is_array($v)) {
                switch ($v[0]) {
                    case 'eq':
                        $result[] = $v[1] . '=:p' . count($params);
                        $params['p' . count($params)] = $v[2];
                        break;
                    case 'gt':
                        $result[] = $v[1] . '>:p' . count($params);
                        $params['p' . count($params)] = $v[2];
                        break;
                    case 'lt':
                        $result[] = $v[1] . '<:p' . count($params);
                        $params['p' . count($params)] = $v[2];
                        break;
                    case 'ge':
                        $result[] = $v[1] . '>=:p' . count($params);
                        $params['p' . count($params)] = $v[2];
                        break;
                    case 'le':
                        $result[] = $v[1] . '<=:p' . count($params);
                        $params['p' . count($params)] = $v[2];
                        break;
                    case 'ne':
                        $result[] = $v[1] . '!=:p' . count($params);
                        $params['p' . count($params)] = $v[2];
                        break;
                    case 'isnull':
                        $result[] = $v[1] . ' IS NULL';
                        break;
                    case 'notnull':
                        $result[] = $v[1] . ' IS NOT NULL';
                        break;
                    case 'like':
                        $result[] = $v[1] . " LIKE " . $this->escape($v[2]) . "";
                        break;
                    case 'in':
                        $arr = array();
                        foreach ($v[2] as $item) {
                            $arr[] = $this->escape($item);
                        }
                        $result[] = $v[1] . " IN(" . implode(',', $arr) . ")";
                        break;
                    case 'notin':
                        $arr = array();
                        foreach ($v[2] as $item) {
                            $arr[] = $this->escape($item);
                        }
                        $result[] = $v[1] . " NOT IN(" . implode(',', $arr) . ")";
                        break;
                    case 'between':
                        $cnt = count($params);
                        $result[] = sprintf('%s BETWEEN :p%d AND :p%d', $v[1], cnt, cnt + 1);
                        break;
                }
            }
        }
        return 'WHERE ' . implode(' ', $result);
    }

    private function handleError($err)
    {
        if ($err[1]) {
            throw new Exception($err[2]);
        }
    }
}
