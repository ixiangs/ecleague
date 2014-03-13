<?php
namespace Toys\Data;

abstract class BaseProvider {

    protected $settings = null;

    protected function __construct($settings){
        $this->settings = $settings;
    }

    public function getSettings(){
        return $this->settings;
    }

    function isConnected(){}
    function inTransaction(){}
    function escape($value){}
    function connect(){}
    function disconnect(){}
    function begin(){}
    function commit(){}
    function rollback(){}
    function getAffectedRows(){}
    function getLastInsertId(){}
    function execute($sql, $arguments = array()){}
    function fetch($sql, $arguments = array()){}
    function insert($statement){}
    function update($statement){}
    function delete($statement){}
    function select($statement){}
    function create($statement){}
}
