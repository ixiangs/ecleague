<?php
use Toy\Unit\TestCase;
use Toy\Db\Configuration;
use Toy\Db;
use Toy\Db\Query;
use Toy\Joy;

class DataTestCase extends TestCase
{

    private $_db = null;

    public function __construct()
    {
        Configuration::addConnection('default', 'Toy\Db\Provider\MysqlProvider', 'mysql:host=localhost;dbname=Toy', 'root', '');
        Configuration::$trace = true;
        $this->_db = Joy::db();
    }

    public function testConnect()
    {
        $this->assertEqual(true, $this->_db->isConnected());
    }

    public function testInsert()
    {
        $b = $this->_db->insert('test1', array('fullname' => 'ronald', 'age' => 40));
        $this->assertEqual(true, $b);
        $lastId = $this->_db->getLastInsertId();
        $this->assertEqual(true, $lastId > 0);
    }

    public function testUpdate()
    {
        $b = $this->_db->insert('test1', array('fullname' => 'forupdate', 'age' => 40));
        $this->assertEqual(true, $b);
        $lastId = $this->_db->getLastInsertId();
        $b = $this->_db->update(
            'test1',
            array('fullname' => 'updated' . time()),
            array(array('id =', $lastId)));
        $this->assertEqual(true, $b);
        $b = $this->_db->update(
            'test1',
            array('fullname' => 'updated before'),
            array(array('id <', $lastId), array('id =', $lastId)));
        $b = $this->_db->update(
            'test1',
            array('fullname' => 'updated or'),
            array(array('id <', $lastId), 'OR', array('id =', $lastId)));
        $b = $this->_db->delete(
            'test1',
            array(array('id =', $lastId)));
        $this->assertEqual(true, $b);
    }

    public function testFetch()
    {
        $r = $this->_db->fetch('SELECT * FROM test1');
        $this->assertEqual(true, count($r->rows) > 0);
        $this->assertEqual($r->rows[0]['id'], $r->getFirstValue());
        $arr = $r->combineColumns('id', 'fullname');
        $this->assertEqual(true, count($arr) > 0);
    }

    public function testQuery()
    {
        $q = new Query();
        $r1 = $this->_db->query($q->from('test1'));
        $r2 = $this->_db->fetch('SELECT * FROM test1');
        $this->assertEqual($r1->rowCount(), $r2->rowCount());

        $r1 = $this->_db->query($q->andFilter('id > ', 50));
        $r2 = $this->_db->fetch('SELECT * FROM test1 WHERE id > 50');
        $this->assertEqual($r1->rowCount(), $r2->rowCount());

        $r1 = $this->_db->query($q->resetWhere()->andFilter('id > ', 50)->andFilter('fullname =', 'updated or'));
        $r2 = $this->_db->fetch("SELECT * FROM test1 WHERE id > 50 AND fullname='updated or'");
        $this->assertEqual($r1->rowCount(), $r2->rowCount());

        $r1 = $this->_db->query($q->resetWhere()->andFilter('id > ', 50)->orFilter('fullname =', 'updated or'));
        $r2 = $this->_db->fetch("SELECT * FROM test1 WHERE id > 50 OR fullname='updated or'");
        $this->assertEqual($r1->rowCount(), $r2->rowCount());

        $r1 = $this->_db->query($q->resetWhere()->andFilter('id in ', array(1, 2, 3)));
        $r2 = $this->_db->fetch("SELECT * FROM test1 WHERE id IN (1, 2, 3)");
        $this->assertEqual($r1->rowCount(), $r2->rowCount());

        $r1 = $this->_db->query($q->resetWhere()->andFilter('id > ', 50)->desc('fullname')->asc('age'));
        $r2 = $this->_db->fetch("SELECT * FROM test1 WHERE id > 50 ORDER BY fullname DESC, age ASC");
        $this->assertEqual($r1->rowCount(), $r2->rowCount());

        $r1 = $this->_db->query($q->resetWhere()->andFilter('id > ', 50)->desc('fullname')->asc('age')->limit(10));
        $r2 = $this->_db->fetch("SELECT * FROM test1 WHERE id > 50 ORDER BY fullname DESC, age ASC LIMIT 0, 10");
        $this->assertEqual($r1->rowCount(), $r2->rowCount());
        $len = $r1->rowCount();
        for ($i = 0; $i < $len; $i++) {
            if ($r1->rows[$i]['id'] != $r2->rows[$i]['id']) {
                $this->fail('result not same');
            }
        }
    }
}
