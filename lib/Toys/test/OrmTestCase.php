<?php
use Toys\Unit\TestCase;
use Toys\Data\Db;
use Toys\Joy;

class TestModel extends \Toys\Orm\ModelBase{
	
	protected function getMetadata(){
		return array(
			'table'=>'test2',
			'properties'=>array(
				\Toys\Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
				\Toys\Orm\StringProperty::create('fullname')->setRangeLength(6, 12),
				\Toys\Orm\IntegerProperty::create('age')->setRangeValue(1, 100)->setUpdateable(false),
				\Toys\Orm\StringProperty::create('mobile')->setNullable(false)->setRangeLength(11, 11)->setRegexp('/^1\d{10}$/')
			)
		);
	}
}

class OrmTestCase extends TestCase {
	
	public function testEntity(){
		$tm = new TestModel();
		$this->assertEqual($tm->getEntity()->getTableName(), 'test2');
		$this->assertEqual(4, count($tm->getEntity()->getProperties()));
		
		$prop = $tm->getEntity()->getIdProperty();
		$this->assertEqual(true, $prop->getPrimaryKey());
		$this->assertEqual(true, $prop->getAutoIncrement());
		$this->assertEqual(false, $prop->getNullable());
		$this->assertEqual(true, $prop->getUnique());
		$this->assertEqual(false, $prop->getInsertable());
		$this->assertEqual(false, $prop->getUpdateable());
		$this->assertEqual('Toys\Orm\IntegerProperty', get_class($prop));
		
		$prop = $tm->getEntity()->getProperty('fullname');
		$this->assertEqual(false, $prop->getPrimaryKey());
		$this->assertEqual(false, $prop->getAutoIncrement());
		$this->assertEqual(true, $prop->getNullable());
		$this->assertEqual(false, $prop->getUnique());
		$this->assertEqual(6, $prop->getMinLength());
		$this->assertEqual(12, $prop->getMaxLength());
		$this->assertEqual(true, $prop->getInsertable());
		$this->assertEqual(true, $prop->getUpdateable());		
		$this->assertEqual('Toys\Orm\StringProperty', get_class($prop));	
		
		$prop = $tm->getEntity()->getProperty('age');
		$this->assertEqual(false, $prop->getPrimaryKey());
		$this->assertEqual(false, $prop->getAutoIncrement());
		$this->assertEqual(true, $prop->getNullable());
		$this->assertEqual(false, $prop->getUnique());
		$this->assertEqual(1, $prop->getMinValue());
		$this->assertEqual(100, $prop->getMaxValue());
		$this->assertEqual(true, $prop->getInsertable());
		$this->assertEqual(false, $prop->getUpdateable());			
		$this->assertEqual('Toys\Orm\IntegerProperty', get_class($prop));
		
		$prop = $tm->getEntity()->getProperty('mobile');
		$this->assertEqual(false, $prop->getPrimaryKey());
		$this->assertEqual(false, $prop->getAutoIncrement());
		$this->assertEqual(false, $prop->getNullable());
		$this->assertEqual(false, $prop->getUnique());
		$this->assertEqual(true, $prop->getInsertable());
		$this->assertEqual(true, $prop->getUpdateable());			
		$this->assertEqual('/^1\d{10}$/', $prop->getRegexp());
		$this->assertEqual('Toys\Orm\StringProperty', get_class($prop));		
	}

	public function testValidate(){
		$tm = new TestModel();
		$tm->setFullname('a')->setAge(0)->setMobile('123456');
		$vr = $tm->validate();
		$this->assertEqual(3, count($vr));
		
		$tm->setFullname('ronald');
		$vr = $tm->validate();
		$this->assertEqual(2, count($vr));
		
		$tm->setAge(102);
		$vr = $tm->validate();
		$this->assertEqual(2, count($vr));
		
		$tm->setAge(99);
		$vr = $tm->validate();
		$this->assertEqual(1, count($vr));	
		
		$tm->setMobile('22345678901');
		$vr = $tm->validate();
		$this->assertEqual(1, count($vr));	
		
		$tm->setMobile('15815922222');
		$vr = $tm->validate();
		$this->assertEqual(true, $vr);							
	}
	
	public function testSave(){
		$tm1 = TestModel::create()->setFullname('ronald')->setAge(30)->setMobile('15815922222');
		$b1 = $tm1->insert();
		$this->assertEqual(true, $b1);
				
		$tm2 = TestModel::create()->setFullname('ronald')->setAge(30)->setMobile('15815922222');
		$b2 = $tm2->insert();
		$this->assertEqual(true, $b2);
		
		$b3 = $tm2->setAge(50)->update();
		$this->assertEqual(true, $b3);
		
		$tm3 = TestModel::load($tm2->getId());
		$this->assertEqual(30, $tm3->getAge());
		
		$tm4 = TestModel::create()->setFullname('ronald')->setAge(30)->setMobile('15815922222');
		$tm4->delete();
	}
	
	public function testFind(){
		$tm1 = TestModel::create()->setFullname('ronald')->setAge(30)->setMobile('15815922222');
		$tm1->insert();
		$tm2 = TestModel::load($tm1->getId());
		$this->assertEqual('ronald', $tm2->getFullname());
		$this->assertEqual('15815922222', $tm2->getMobile());
		$this->assertEqual(30, $tm2->getAge());
		
		$arr = TestModel::find(array('id <'=>$tm1->getId()))->execute()->getModelArray();
		$this->assertEqual(true, count($arr) > 0);
	}	
}
