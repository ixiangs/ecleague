<?php
namespace Toys\Data\Db;

use Toys\Data\Exception;
use Toys\Data\Db\Base;
use Toys\Data\Configuration;
use Toys\Data\Result;

abstract class PdoProvider extends BaseProvider
{

    protected $connection = NULL;

    protected function __construct($settings)
    {
        parent::__construct($settings);
    }

    public function __destruct()
    {
        $this->connection = NULL;
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

    public function disconnect()
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
        if($statement === false){
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
        if($statement === false){
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

    private function handleError($err)
    {
        if ($err[1]) {
            throw new Exception($err[2]);
        }
    }
}
