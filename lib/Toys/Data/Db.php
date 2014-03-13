<?php
namespace Toys\Data;

final class Db {

	private $_provider = null;
	
	private function __construct(IProvider $provider) {
		$this->_provider = $provider;
	}

	public function getSettings(){
		return $this->_provider->getSettings();
	}
	
	public function isConnected(){
		return $this->_provider->isConnected();
	}
	
	public function inTransaction(){
		return $this->_provider->inTransaction();
	}
	
	public function escape($value){
		return $this->_provider->escape($value);
	}
	
	public function connect(){
		$this->_provider->connect();
		return $this;
	}
	
	public function disconnect(){
		$this->_provider->disconnect();
		return $this;
	}
	public function begin(){
		$this->_provider->begin();
		return $this;
	}
	
	public function commit(){
		$this->_provider->commit();
		return $this;
	}
	
	public function rollback(){
		$this->_provider->rollback();
		return $this;
	}
	
	public function getAffectedRows(){
		return $this->_provider->getAffectedRows();
	}
	
	public function getLastInsertId(){
		return $this->_provider->getLastInsertId();
	}
	
	public function execute($sql, $arguments){
		return $this->_provider->execute($sql, $arguments);
	}
	
	public function fetch($sql, $arguments = array()){
		return $this->_provider->fetch($sql, $arguments);
	}
	
	public function insert($table, $values){
		return $this->_provider->insert($table, $values);
	}
	
	public function update($table, $values, $conditions = array()){
		return $this->_provider->update($table, $values, $conditions);
	}
	
	public function delete($table, $conditions = array()){
		return $this->_provider->delete($table, $conditions);
	}
	
	public function query($query){
		return $this->_provider->query($query);
	}

	private static $_dbs = array();
	public static function current($name = null) {
		if(empty($name)){
			$name = Configuration::$defaultConnection;
		}
		if (!array_key_exists($name, self::$_dbs)) {
			self::$_dbs[$name] = new self(self::create($name));
		}
		$r = self::$_dbs[$name];
		if(!$r->isConnected()){
			$r->connect();
		}
		return $r;
	}

	public static function create($name = null) {
		$settings = null;
		if (!is_null($name)) {
			$settings = Configuration::$connectionSettings[$name];
		}

		if (is_null($settings)) {
			$settings = Configuration::$connectionSettings[Configuration::$defaultConnection];
		}

		$class = $settings['provider'];
		$provider = new $class($settings);
		return $provider -> connect();
	}
	
	public static function transaction(\Closure $handler, $name = null) {
		$db = self::create($name);
		try{
			$db->begin();
			$handler($db);
			$db->commit();
		}catch(Exception $ex){
			$db->rollback();
		}
	}	

}
