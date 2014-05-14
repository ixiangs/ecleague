<?php
namespace Ixiangs\User;

class Identity{

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

    public function hasAnyBehavior(array $codes){
        foreach($codes as $code){
            if(in_array($code, $this->_behaviors)){
                return true;
            }
        }
        return false;
    }

    public function getAllData(){
        return array(
            'id'=>$this->_id,
            'username'=>$this->_username,
            'roles'=>$this->_roles,
            'behaviors'=>$this->_behaviors,
            'level'=>$this->_level
        );
    }
}