<?php
namespace Toys\Data\Db;

use Toys\Data\Configuration;

abstract class BaseProvider {

    protected $settings = null;

    protected function __construct($settings){
        $this->settings = $settings;
    }

    public function getSettings(){
        return $this->settings;
    }

    protected function log($sql, $arguments){
        $content = $sql;
        foreach($arguments as $n=>$v){
            $content .= '['.$n.':'.$v.']';
        }
        Configuration::$logger->v($content, 'sql');
    }

    public abstract function isConnected();
    public abstract function inTransaction();
    public abstract function escape($value);
    public abstract function connect();
    public abstract function disconnect();
    public abstract function begin();
    public abstract function commit();
    public abstract function rollback();
    public abstract function getLastInsertId();
    public abstract function execute($sql, $arguments = array());
    public abstract function fetch($sql, $arguments = array());
    public abstract function insert($statement);
    public abstract function update($statement);
    public abstract function delete($statement);
    public abstract function select($statement);
//    public abstract function create($statement);
}
