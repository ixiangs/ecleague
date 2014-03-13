<?php

use Toys\Joy;
use Toys\Unit\TestCase;

class AuthTestCase extends TestCase {

	public function testBehaviorModel() {
		$m = \User\BehaviorModel::find() -> andFilter('code =', 'add_order') -> execute() -> getFirstModel();
		if ($m) {
			$m -> delete();
		}

		$m = new \User\BehaviorModel();
		$b = $m -> setCode('add_order') -> setLabel('新增订单') -> setEnabled(true) -> insert();
		$this -> assertTrue($b);
		$this -> assertNotEmpty($m -> id);

		$m = \User\BehaviorModel::find() -> andFilter('code =', 'add_order') -> execute() -> getFirstModel();
		$this -> assertTrue($m -> getEnabled());
		$this -> assertEqual('新增订单', $m -> getLabel());
		$b = $m -> setLabel('修改过的') -> setEnabled(false) -> update();
		$this -> assertTrue($b);
		$m = \User\BehaviorModel::load($m -> id);
		$this -> assertEqual('修改过的', $m -> label);
		$this -> assertFalse($m -> getEnabled());
	}

	public function testRoleModel() {
		$m = \User\RoleModel::find() -> andFilter('code =', 'role1') -> execute() -> getFirstModel();
		if ($m) {
			$m -> delete();
		}

		$m = new \User\RoleModel();
		$b = $m -> setCode('role1') -> setLabel('role one') -> setEnabled(true) -> setBehaviorIds(array(1, 2, 3, 4)) -> insert();
		$this -> assertTrue($b);
		$this -> assertNotEmpty($m -> id);

		$m = \User\RoleModel::find() -> andFilter('code =', 'role1') -> execute() -> getFirstModel();
		$this -> assertTrue($m -> getEnabled());
		$this -> assertEqual('role one', $m -> getLabel());
		$this -> assertEqual(4, count($m -> behavior_ids));
		$this -> assertEqual(1, $m -> behavior_ids[0]);

		$b = $m -> setLabel('role two') -> setEnabled(false) -> update();
		$this -> assertTrue($b);

		$m = \User\RoleModel::load($m -> id);
		$this -> assertEqual('role two', $m -> label);
		$this -> assertFalse($m -> getEnabled());
		
		$models = $m->getBehaviors()->execute()->getModelArray();
		$this->assertEqual(0, count($models));
	}

	public function testAccountModel(){
		$m = \User\AccountModel::find() -> andFilter('username =', 'administrator') -> execute() -> getFirstModel();
		if ($m) {
			$m -> delete();
		}
		$m = \User\AccountModel::find() -> andFilter('username =', 'administrator2') -> execute() -> getFirstModel();
		if ($m) {
			$m -> delete();
		}		
		
		$m = \User\AccountModel::create(array(
			'username'=>'administrator',
			'password'=>'123456',
			'status'=>\User\AccountModel::STATUS_ACTIVATED,
			'email'=>'administrator@aaa.com',
			'level'=>\User\AccountModel::LEVEL_ADMINISTRATOR,
			'role_ids'=>array(1, 2, 3, 4, 5)
		));
		$b = $m -> insert();
		$this -> assertTrue($b);
		$this -> assertNotEmpty($m -> id);
		
		$m2 = \User\AccountModel::load($m->getId());
		$this->assertTrue($m2->comparePassword('123456'));
		$this->assertFalse($m2->comparePassword('1234567'));
		$this->assertEqual($m2->getEmail(), 'administrator@aaa.com');
		$this->assertEqual($m2->getStatus(), \User\AccountModel::STATUS_ACTIVATED);
		$this->assertEqual($m2->getLevel(), \User\AccountModel::LEVEL_ADMINISTRATOR);
		
		$m3 = \User\AccountModel::create(array(
			'username'=>'administrator',
			'password'=>'123456',
			'status'=>\User\AccountModel::STATUS_ACTIVATED,
			'email'=>'administrator@aaa.com',
			'level'=>\User\AccountModel::LEVEL_ADMINISTRATOR,
			'role_ids'=>array(1, 2, 3, 4, 5)
		));
		
		$s = $m3->register();
		$this->assertEqual(\User\AccountModel::ERROR_ACCOUNT_REPEATED, $s);
		$m3->setUsername('administrator2');
		$s = $m3->register();
		$this->assertTrue($s);		
		
		\User\AccountModel::modifyPassword($m3->getId(), '123456', 'abcdef');
		
		$m4 = \User\AccountModel::load($m3->getId());
		$this->assertTrue($m4->comparePassword('abcdef'));
	}

	public function testLogin(){
		$m = \User\AccountModel::find() -> andFilter('username =', 'testlogin') -> execute() -> getFirstModel();
		if (!$m) {
			$m = \User\AccountModel::create(array(
				'username'=>'testlogin',
				'password'=>'123456',
				'status'=>\User\AccountModel::STATUS_NONACTIVATED,
				'email'=>'administrator@aaa.com',
				'level'=>\User\AccountModel::LEVEL_ADMINISTRATOR,
				'role_ids'=>array(1, 2, 3, 4, 5)
			));
			$m->insert();
		}		
		list($err, $ol) = \User\AccountModel::login('admin', '123456');
		$this->assertEqual(\User\AccountModel::ERROR_NOT_FOUND, $err);
		list($err, $ol) = \User\AccountModel::login('testlogin', '12342256');
		$this->assertEqual(\User\AccountModel::ERROR_PASSWORD, $err);
		list($err, $ol) = \User\AccountModel::login('testlogin', '123456');
		$this->assertEqual(\User\AccountModel::ERROR_NONACTIVATED, $err);		
	}
}
