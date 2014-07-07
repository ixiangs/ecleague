<?php
namespace Components\Realty\Models;

use Toy\Orm;
use Components\Realty\Constant;

class UptownModel extends Orm\Model
{

}

UptownModel::registerMetadata(array(
    'table' => Constant::TABLE_UPTOWN,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('developer_id')->setNullable(false),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\StringProperty::create('address')->setNullable(false)
    )
));