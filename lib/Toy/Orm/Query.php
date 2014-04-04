<?php
namespace Toy\Orm;

use Toy\Data\Helper;
use Toy\Data\Result;

use Toy\Data\Sql\SelectStatement;

class Query extends SelectStatement
{
    public function execute($db = null)
    {
        $cdb = $db ? $db : Helper::openDb();
        return $cdb->select($this);
    }
}