<?php

class SqliteTestCase extends Toy\Unit\TestCase{

    private $_db = null;

    public function __construct(){
        $this->_db = new \Toy\Data\Db\SqliteProvider(array('dsn' => 'sqlite:'.dirname(__FILE__).DS.'testdb.db3'));
        $this->_db->connect();
    }

    public function testInsert(){
        $is = new \Toy\Data\Sql\InsertStatement('people', array(
            'fullname'=>'ronald',
            'age'=>20,
            'gender'=>1,
            'address'=>'china'
        ));
        $this->_db->insert($is);
        $this->assertTrue($this->_db->getLastInsertId() > 0);
    }

    public function testUpdate(){
        $is = new \Toy\Data\Sql\InsertStatement('people', array(
            'fullname'=>'ronald',
            'age'=>20,
            'gender'=>1,
            'address'=>'china'
        ));
        $this->_db->insert($is);

        $lid = $this->_db->getLastInsertId();
        $us = new \Toy\Data\Sql\UpdateStatement('people', array(
            'fullname'=>'ronald'.$lid,
            'age'=>20 + $lid
        ));
        $us->eq('id', $lid);
        $this->_db->update($us);
    }

    public function testDelete(){
        $is = new \Toy\Data\Sql\InsertStatement('people', array(
            'fullname'=>'ronald',
            'age'=>20,
            'gender'=>1,
            'address'=>'china'
        ));
        $this->_db->insert($is);

        $lid = $this->_db->getLastInsertId();
        $us = new \Toy\Data\Sql\DeleteStatement('people');
        $us->eq('id', $lid);
        $this->_db->delete($us);
    }

    public function testSelect(){
        $ss = new \Toy\Data\Sql\SelectStatement('people');
        $rs1 = $this->_db->select($ss);
        $rs2 = $this->_db->fetch('SELECT * FROM people');
        $this->assertEqual(count($rs1->rows), count($rs2->rows));

        $ss = new \Toy\Data\Sql\SelectStatement('people', array('address'));
        $rs1 = $this->_db->select($ss);
        $rs2 = $this->_db->fetch('SELECT address FROM people');
        $this->assertEqual($rs1->getFirstValue(), $rs2->getFirstValue());

        $ss = new \Toy\Data\Sql\SelectStatement('people');
        $ss->gt('id', 10);
        $rs1 = $this->_db->select($ss);
        $rs2 = $this->_db->fetch('SELECT * FROM people WHERE id>10');
        $this->assertEqual(count($rs1->rows), count($rs2->rows));

        $ss = new \Toy\Data\Sql\SelectStatement('people');
        $ss->gt('id', 10)->lt('id', 50)->orNext()->eq('fullname', 'ronald');
        $rs1 = $this->_db->select($ss);
        $rs2 = $this->_db->fetch("SELECT * FROM people WHERE id>10 AND id<50 OR fullname='ronald'");
        $this->assertEqual(count($rs1->rows), count($rs2->rows));

        $ss = new \Toy\Data\Sql\SelectStatement('people');
        $ss->limit(12);
        $rs1 = $this->_db->select($ss);
        $rs2 = $this->_db->fetch("SELECT * FROM people LIMIT 12");
        $this->assertEqual(count($rs1->rows), count($rs2->rows));

        $ss = new \Toy\Data\Sql\SelectStatement('people');
        $ss->limit(12, 5);
        $rs1 = $this->_db->select($ss);
        $rs2 = $this->_db->fetch("SELECT * FROM people LIMIT 5, 12");
        $this->assertEqual(count($rs1->rows), count($rs2->rows));

        $ss = new \Toy\Data\Sql\SelectStatement('car');
        $ss->join('people', 'people.id', 'car.people_id');
        $rs1 = $this->_db->select($ss);
        $rs2 = $this->_db->fetch("SELECT * FROM car INNER JOIN people ON people.id=car.people_id");
        $this->assertEqual(count($rs1->rows), count($rs2->rows));

        $ss = new \Toy\Data\Sql\SelectStatement('car');
        $ss->join('people', 'people.id', 'car.people_id')->desc('id')->asc('fullname');
        $rs1 = $this->_db->select($ss);
        $rs2 = $this->_db->fetch("SELECT * FROM car INNER JOIN people ON people.id=car.people_id ORDER BY id DESC, fullname ASC");
        $this->assertEqual(count($rs1->rows), count($rs2->rows));
    }
}