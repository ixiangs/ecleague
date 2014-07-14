<?php
namespace Void\Auth;

use Toy\Orm;
use Void\Auth\Constant;

class BehaviorModel extends Orm\Model
{

}

BehaviorModel::registerMetadata(array(
    'table' => Constant::TABLE_BEHAVIOR,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true)->setUpdateable(false),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)
    )
));