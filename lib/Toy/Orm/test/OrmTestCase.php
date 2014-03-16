<?php

class PeopleModel extends \Toy\Orm\Model
{

}

\Toy\Orm\Entity::register('PeopleModel', array(
    'table' => 'people',
    'properties' => array(
        \Toy\Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        \Toy\Orm\StringProperty::create('fullname'),
        \Toy\Orm\IntegerProperty::create('age'),
        \Toy\Orm\StringProperty::create('address')
    )
));

class OrmTestCase extends Toy\Unit\TestCase
{

    private $_people = null;
    private $_db = null;

    public function __construct()
    {
        $this->_people = PeopleModel::create();
        $this->_db = \Toy\Data\Helper::openDb();
    }

    public function testEntity(){
        $this->assertNotNull($this->_people->getEntity());
        $this->assertEqual('people', $this->_people->getEntity()->getTableName());
        $this->assertEqual(4, count($this->_people->getEntity()->getProperties()));
        $idp = $this->_people->getEntity()->getProperty('id');
        $this->assertEqual(true, $idp->getPrimaryKey());
        $this->assertEqual(true, $idp->getAutoIncrement());
        $this->assertEqual(true, $idp->getUnique());
        $this->assertEqual(false, $idp->getNullable());
    }

    public function testSaveLoad(){
        $m = PeopleModel::create(array('fullname'=>'orm'))
            ->setAge(50)
            ->setAddress('guangdong');
        $m->insert();
        $nid = $m->getId();
        $this->assertTrue($nid > 0);

        $m = PeopleModel::create(array('fullname'=>'test', 'age'=>30, 'address'=>'shunde'));
        $m->insert();
        $m->setAddress('foshan')->update();

        $m = PeopleModel::create(array('fullname'=>'delete', 'age'=>30, 'address'=>'shunde'));
        $m->insert();
        $m->delete();

        $m = PeopleModel::load($nid);
        $this->assertTrue($m->getId() > 0);
        $this->assertEqual(50, $m->getAge());
        $this->assertEqual('orm', $m->getFullname());
        $this->assertEqual('guangdong', $m->getAddress());

        $m = PeopleModel::merge($nid, array('fullname'=>'world'));
        $this->assertTrue($m->getId() > 0);
        $this->assertEqual(50, $m->getAge());
        $this->assertEqual('world', $m->getFullname());
        $this->assertEqual('guangdong', $m->getAddress());
    }

    public function testQuery(){
        $rs1 = PeopleModel::find()->execute();
        $rs2 = $this->_db->fetch('SELECT * FROM people');
        $this->assertEqual(count($rs1->rows), count($rs2->rows));

        $rs1 = PeopleModel::find()->execute()->getModelArray();
        $this->assertTrue(count($rs1) > 0);
    }
}