<?php
namespace Components\Auth;

class Identity{

	private $_id = null;
	private $_username = null;
	private $_roles = null;
	private $_behaviors = null;
	private $_domains = null;

	public function __construct($id, $username, $domains, $roles, $behaviors){
		$this->_id = $id;
		$this->_username = $username;
		$this->_domains = $domains;
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

    public function getDomains(){
        return $this->_domains;
    }

    public function hasDomain($domain){
        return in_array($domain, $this->_domains);
    }

    public function hasRole($code){
        if($this->_roles == '*'){
            return true;
        }
        return in_array($code, $this->_roles);
    }

    public function hasAnyRole($codes){
        if($this->_roles == '*'){
            return true;
        }
        foreach($codes as $code){
            if(in_array($code, $this->_roles)){
                return true;
            }
        }
        return false;
    }
	
	public function hasBehavior($code){
        if($this->_behaviors == '*'){
            return true;
        }
		return in_array($code, $this->_behaviors);
	}

    public function hasAnyBehavior($codes){
        if($this->_behaviors == '*'){
            return true;
        }
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
            'domains'=>$this->_domains
        );
    }

    public function isAuthenticated(){
        return !empty($this->_id);
    }

    static public function guest(){
        return new self(null, null, array(), array(), array());
    }
}