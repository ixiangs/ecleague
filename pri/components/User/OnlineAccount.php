<?php
namespace User;

class OnlineAccount{

	private $_id = null;
	private $_username = null;
	private $_roles = null;
	private $_behaviors = null;
	private $_level = null;

	public function __construct($id, $username, $level, $roles, $behaviors){
		$this->_id = $id;
		$this->_username = $username;
		$this->_level = $level;
		$this->_roles = $roles;
		$this->_behaviors = $behaviors;
	}

	public function getId(){
		return $this->_id;
	}

	public function getUsername(){
		return $this->_username;
	}
	
	public function getLevel(){
		return $this->_level;
	}	

	public function getRoles(){
		return $this->_roles;
	}

	public function getBehaviors(){
		return $this->_behaviors;
	}
	
	public function hasBehavior($code){
		return in_array($code, $this->_behaviors);
	}
}