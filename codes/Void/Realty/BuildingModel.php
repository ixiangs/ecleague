<?php
namespace Void\Realty;

use Toy\Orm, Toy\Orm\Db;

class BuildingModel extends Orm\Model
{

}

BuildingModel::registerMetadata(array(
    'table' => VOID_REALTY_TABLE_BUILDING,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\IntegerProperty::create('floor_count')->setNullable(false),
        Orm\IntegerProperty::create('room_count')->setNullable(false),
        Orm\IntegerProperty::create('uptown_id')->setNullable(false)->setUpdateable(false)
    )
));