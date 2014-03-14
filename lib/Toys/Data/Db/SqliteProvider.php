<?php
namespace Toys\Data\Db;


class SqliteProvider extends PdoProvider
{

    public function __construct($settings)
    {
        parent::__construct($settings);
    }

    public function connect()
    {
        if (!$this->connection) {
            $this->connection = new \PDO($this->settings['dsn']);
        }
        return $this;
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

    public function select($query)
    {
        $params = array();
        if (count($query->getFields()) > 0) {
            $sql = 'SELECT ' . implode(',', $query->getFields());
        } else {
            $sql = 'SELECT *';
        }
        $sql .= ' FROM ' . $query->getTable();

        $joins = $query->getJoins();
        foreach ($joins as $v) {
            $sql .= ' ' . strtoupper($v[0]) . ' JOIN ' . $v[1] . ' ON ' . $v[2] . '=' . $v[3];
        }

        if (count($query->getConditions()) > 0) {
            $sql .= ' ' . $this->parseWhere($query->getConditions(), $params);
        }

        if (count($query->getOrderBy()) > 0) {
            $arr = array();
            foreach ($query->getOrderBy() as $f => $d) {
                $arr[] = $f . ' ' . $d;
            }
            $sql .= ' ORDER BY ' . implode(',', $arr);
        }

        $offset = $query->getOffset();
        $limit = $query->getLimit();
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
                        $result[] = $v[1] . " LIKE '" . $this->escape($v[2]) . "'";
                        break;
                    case 'in':
                        $arr = array();
                        foreach ($v[2] as $item) {
                            $arr[] = "'" . $this->escape($item) . "'";
                        }
                        $result[] = $v[1] . " IN(" . implode(',', $arr) . ")";
                        break;
                    case 'notin':
                        $arr = array();
                        foreach ($v[2] as $item) {
                            $arr[] = "'" . $this->escape($item) . "'";
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
}
