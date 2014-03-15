<?php
namespace User;

use Toys\Orm;
use Toys\Util\EncryptUtil;
use User\OnlineAccount;
use Toys\Data\Db;

class AccountModel extends Orm\ModelBase {

	const TABLE_NAME = '{t}user_account';

	const ERROR_NOT_FOUND = 1;
	const ERROR_PASSWORD = 2;
	const ERROR_DISABLED = 3;
	const ERROR_NONACTIVATED = 4;
	const ERROR_ACCOUNT_REPEATED = 5;
	const ERROR_UNKNOW = 99;

	const STATUS_ACTIVATED = 1;
	const STATUS_NONACTIVATED = 2;
	const STATUS_DISABLED = 3;

	const LEVEL_ADMINISTRATOR = 1;
	const LEVEL_NORMAL = 2;

	protected function beforeInsert() {
		$this -> password = EncryptUtil::encryptPassword($this -> password);
	}

	public function comparePassword($other) {
		return $this -> password == EncryptUtil::encryptPassword($other);
	}

	protected function getMetadata() {
		return array(
		'table' => AccountModel::TABLE_NAME, 
		'properties' => array(
			Orm\IntegerProperty::create('id') -> setPrimaryKey(true) -> setAutoIncrement(true), 
			Orm\StringProperty::create('username') -> setUnique(true) -> setUpdateable(false), 
			Orm\StringProperty::create('password')->setUpdateable(false), 
			Orm\StringProperty::create('email'), Orm\IntegerProperty::create('status'), 
			Orm\IntegerProperty::create('level'), Orm\ArrayProperty::create('role_ids')));
	}

	public function register() {
		$m = static::find(array('username =' => $this -> getUsername())) -> execute() -> getFirstModel();
		if ($m) {
			return self::ERROR_ACCOUNT_REPEATED;
		}

		$db = Db::current();
		try{
			$db->begin();
			if ($this -> insert($db)) {
				ProfileModel::create()->setAccountId($this->getId())->insert($db);
				$db->commit();
				return true;
			}
			$db->rollback();
			return self::ERROR_UNKNOW;
		}catch(Exception $ex){
			$db->rollback();
			return self::ERROR_UNKNOW;
		}
	}

	public static function checkUsername($username) {
		$m = self::find(array('username =' => $username)) -> count() -> execute() -> getFirstValue();
		return $m > 0;
	}

	public static function modifyPassword($id, $old, $new) {
		$m = self::load($id);
		if (!$m -> comparePassword($old)) {
			return self::ERROR_PASSWORD;
		}
		
		if ($m -> comparePassword($old)) {
			if(!\Toys\Joy::db()->update(
				self::TABLE_NAME, 
				array('password'=>EncryptUtil::encryptPassword($new)),
				array('id =', $id))){
					return self::ERROR_UNKNOW;
				}
		}
		return true;
	}

	public static function login($username, $password) {
		$m = self::find(array('username =' => $username)) -> execute() -> getFirstModel();
		if (empty($m)) {
			return array(self::ERROR_NOT_FOUND, null);
		}
		if (!$m -> comparePassword($password)) {
			return array(self::ERROR_PASSWORD, null);
		}
		if ($m -> status == self::STATUS_NONACTIVATED) {
			return array(self::ERROR_NONACTIVATED, null);
		}
		if ($m -> status == self::STATUS_DISABLED) {
			return array(self::STATUS_DISABLED, null);
		}

		$behaviorCodes = array();
		$roleCodes = array();
		$roleIds = $m->getRoleIds();
		if(count($roleIds) > 0){
			$roles = RoleModel::find(array('id in' => $roleIds, 'enabled =' => 1)) -> execute() -> combineColumns('code', 'behavior_ids');
			$roleCodes = array_keys($roles);
			if(count($roleCodes) > 0){
				$behaviorIds = array();
				foreach ($roles as $code => $bidArr) {
					if(!empty($bidArr)){
						$behaviorIds = array_merge($behaviorIds, explode(',', $bidArr));
					}
				}
				$behaviorCodes = BehaviorModel::find(array('id in' => $behaviorIds, 'enabled =' => 1)) 
														-> select('code') 
														-> execute()
														-> getColumnValues('code');
			}
		}

		return array(true, new OnlineAccount($m -> id, $m -> username, $m->level, $roleCodes, $behaviorCodes));
	}

	public static function activate($id) {
		return static::create(array('id' => $id, 'status' => self::STATUS_ACTIVATED)) -> update(array('status'));
	}

	public static function freeze($id) {
		return static::create(array('id' => $id, 'status' => self::STATUS_DISABLED)) -> update(array('status'));
	}

}
