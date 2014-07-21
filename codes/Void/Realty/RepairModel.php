<?php
namespace Void\Realty;

use Toy\Orm, Toy\Orm\Db;
use Void\System;
use Void\Realty\Constant;

class RepairModel extends Orm\Model
{

}

RepairModel::registerMetadata(array(
    'table' => Constant::TABLE_REPAIR,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('contacts')->setNullable(false),
        Orm\StringProperty::create('phone')->setNullable(false),
        Orm\StringProperty::create('building')->setNullable(false),
        Orm\IntegerProperty::create('floor')->setNullable(false),
        Orm\IntegerProperty::create('room')->setNullable(false),
        Orm\StringProperty::create('content'),
        Orm\IntegerProperty::create('uptown_id')->setNullable(false),
        Orm\IntegerProperty::create('created_time')->setNullable(false),
        Orm\IntegerProperty::create('repairer_id'),
        Orm\IntegerProperty::create('finished_time')
    )
));