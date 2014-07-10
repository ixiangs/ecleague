<?php
namespace Components\Realty\Models;

use Toy\Orm, Toy\Orm\Db;
use Components\System;
use Components\Realty\Constant;

class BuildingModel extends Orm\Model
{

}

BuildingModel::registerMetadata(array(
    'table' => Constant::TABLE_BUILDING,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\IntegerProperty::create('floor_count')->setNullable(false),
        Orm\IntegerProperty::create('room_count')->setNullable(false),
        Orm\IntegerProperty::create('uptown_id')->setNullable(false)->setUpdateable(false)
    )
));