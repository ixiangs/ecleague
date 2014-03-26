<?php

class VersioningModel extends \Toy\Orm\Versioning\Model
{

}

\Toy\Orm\Versioning\Entity::register('VersioningModel', array(
    'table' => 'versioning',
    'properties' => array(
        \Toy\Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        \Toy\Orm\StringProperty::create('code'),
        \Toy\Orm\StringProperty::create('frontend_label'),
        \Toy\Orm\StringProperty::create('backend_label')
    ),
    'mainProperties'=>array(
        'code'
    ),
    'versionProperties'=>array(
        'frontend_label',
        'backend_label'
    )
));

class VersioningTestCase extends Toy\Unit\TestCase
{

    private $_people = null;
    private $_db = null;

    public function __construct()
    {
        $this->_model = VersioningModel::create();
        $this->_db = \Toy\Data\Helper::openDb();
    }

    public function testEntity(){
        $e = $this->_model->getEntity();
        $this->assertEqual('id', $e->getMainIdProperty()->getName());
        $this->assertEqual('version_id', $e->getVersionIdProperty()->getName());
        $this->assertEqual('version_key', $e->getVersionKeyProperty()->getName());
        $this->assertEqual('main_id', $e->getVersionForeignProperty()->getName());
        $this->assertEqual(2, count($e->getMainProperties()));
        $this->assertEqual(5, count($e->getVersionProperties()));
    }

    public function testSaveLoad(){
        $m = VersioningModel::create(array('code'=>'code'.rand(1, 1000), 'frontend_label'=>'frontend', 'backend_label'=>'backend', 'version_key'=>1));
        $m->insert();
        $mid = $m->getId();
        $vid = $m->getVersionId();
        $this->assertTrue($mid > 0);
        $this->assertTrue($vid > 0);

        $m = VersioningModel::create(array('code'=>'code'.rand(1, 1000), 'frontend_label'=>'frontend2', 'backend_label'=>'backend2', 'version_key'=>1));
        $m->insert();
        $m->setBackendLabel('updated2')->update();

        $m = VersioningModel::loadMain(1);
        print_r($m->getAllData());
        die();
//        $m = PeopleModel::create(array('fullname'=>'delete', 'age'=>30, 'address'=>'shunde'));
//        $m->insert();
//        $m->delete();
//
//        $m = PeopleModel::load($nid);
//        $this->assertTrue($m->getId() > 0);
//        $this->assertEqual(50, $m->getAge());
//        $this->assertEqual('orm', $m->getFullname());
//        $this->assertEqual('guangdong', $m->getAddress());
//
//        $m = PeopleModel::merge($nid, array('fullname'=>'world'));
//        $this->assertTrue($m->getId() > 0);
//        $this->assertEqual(50, $m->getAge());
//        $this->assertEqual('world', $m->getFullname());
//        $this->assertEqual('guangdong', $m->getAddress());
    }
//
//    public function testQuery(){
//        $rs1 = PeopleModel::find()->execute();
//        $rs2 = $this->_db->fetch('SELECT * FROM people');
//        $this->assertEqual(count($rs1->rows), count($rs2->rows));
//
//        $rs1 = PeopleModel::find()->execute()->getModelArray();
//        $this->assertTrue(count($rs1) > 0);
//    }
}