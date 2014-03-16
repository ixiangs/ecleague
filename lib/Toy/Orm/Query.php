<?php
namespace Toy\Orm;

use Toy\Data\Sql\SelectStatement;

class Query extends SelectStatement
{

    private $_entity = null;

    public function __construct($me)
    {
        if ($me instanceof Model) {
            $this->_entity = $me->getEntity();
        }
        $this->_entity = $me;
    }

    public function getEntity()
    {
        return $this->_entity;
    }

    public function execute($db = null)
    {
        $cdb = $db ? $db : \Toy\Data\Helper::openDb();
        return new Result($this->_entity, $cdb->select($this)->rows);
    }
}