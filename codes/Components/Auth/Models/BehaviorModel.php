<?php
namespace Components\Auth\Models;

use Toy\Orm;
use Components\Auth\Constant;

class BehaviorModel extends Orm\Model
{

}

BehaviorModel::registerMetadata(array(
    'table' => Constant::TABLE_BEHAVIOR,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true)->setUpdateable(false),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\BooleanProperty::create('enabled')
    )
));