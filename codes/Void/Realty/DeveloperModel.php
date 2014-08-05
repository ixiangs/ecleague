<?php
namespace Void\Realty;

use Toy\Orm;
use Void\Realty\Constant;

class DeveloperModel extends Orm\Model
{

}

DeveloperModel::registerMetadata(array(
    'table' => VOID_REALTY_TABLE_DEVELOPER,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('name')->setNullable(false)
    )
));