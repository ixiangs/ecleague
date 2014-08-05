<?php
namespace Void\Auth;

use Toy\Orm;

class BehaviorModel extends Orm\Model
{

}

BehaviorModel::registerMetadata(array(
    'table' => VOID_AUTH_TABLE_BEHAVIOR,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true)->setUpdateable(false),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)
    )
));