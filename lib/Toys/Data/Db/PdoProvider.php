<?php
namespace Toys\Data\Db;

use Toys\Data\Db\Base;
use Toys\Data\Configuration;
use Toys\Data\Exception;
use Toys\Data\Result;
use Toys\Util\ArrayUtil;

abstract class PdoProvider implements BaseProvider {

    protected $connection = null;
//    protected $isTransaction = false;

    protected function __construct($settings) {
        parent::__construct($settings);
    }

    public function __destruct() {
        $this -> connection = NULL;
//        $this -> isTransaction = false;
    }

    public function isConnected() {
        return $this -> connection ? TRUE : false;
    }

    public function inTransaction() {
        return $this -> connection -> inTransaction();
    }

    public function escape($value) {
        return $this -> connection -> quote($value);
    }

    public function connect() {
        if (!$this -> connection) {
            $this -> connection = new \PDO($this -> _dsn);
//            $this -> connection -> query("SET NAMES '" . $this->_encoding. "'");
        }
        return $this;
    }

    public function disconnect() {
        if ($this -> connection) {
            $this -> connection = NULL;
        }
    }

    public function begin() {
        if (!$this -> inTransaction()) {
            if (!$this -> connection -> beginTransaction()) {
                $this -> handleError($this -> connection -> errorInfo());
            }
//            $this -> _isTransaction = TRUE;
        }
        return $this;
    }

    public function commit() {
        if ($this -> inTransaction()) {
            if (!$this -> connection -> commit()) {
                $this -> handleError($this->connection -> errorInfo());
            }
//            $this -> _isTransaction = false;
        }
        return $this;
    }

    public function rollback() {
        if ($this -> inTransaction()) {
            if (!$this -> connection -> rollback()) {
                $this -> handleError($this->connection -> errorInfo());
            }
//            $this -> _isTransaction = false;
        }
        return $this;
    }

//    public function getAffectedRows() {
//        return $this -> _statement -> rowCount();
//    }

    public function getLastInsertId() {
        return $this -> connection -> lastInsertId();
    }

    public function execute($sql, $arguments = array()) {
        if (Configuration::$trace) {
            $this->log($sql, $arguments);
        }
        $statement = $this -> connection -> prepare(str_replace('{t}', Configuration::$tablePrefix, $sql));
        foreach ($arguments as $name => $value) {
            $statement -> bindValue($name, $value);
        }
        $result = $statement -> execute();
        $statement->closeCursor();
        $this -> handleError($statement -> errorInfo());
        return $result;
    }

    public function fetch($sql, $arguments = array()) {
        if (Configuration::$trace) {
            $this->log($sql, $arguments);
        }
        $statement = $this -> connection -> prepare(str_replace('{t}', Configuration::$tablePrefix, $sql));
        foreach ($arguments as $name => $value) {
            $statement -> bindValue($name, $value);
        }
        $statement -> execute();
        $this -> handleError($statement -> errorInfo());
        $rows = $statement -> fetchAll(\PDO::FETCH_ASSOC);
        $statement -> closeCursor();
        return new Result($rows);
    }

    private function handleError($err) {
        if ($err[1]) {
            throw new Exception($err[2]);
        }
    }
}
