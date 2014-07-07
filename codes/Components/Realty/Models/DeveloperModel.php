<?php
namespace Components\Realty\Models;

use Toy\Orm;
use Components\Realty\Constant;

class DeveloperModel extends Orm\Model
{

}

DeveloperModel::registerMetadata(array(
    'table' => Constant::TABLE_DEVELOPER,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('name')->setNullable(false)
    )
));