<?php
namespace Ixiangs\User;

use Toy\Orm;

class BehaviorModel extends Orm\Model
{

}

BehaviorModel::register(array(
    'table' => Constant::TABLE_BEHAVIOR,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true)->setUpdateable(false),
        Orm\StringProperty::create('name'),
        Orm\StringProperty::create('url'),
        Orm\BooleanProperty::create('enabled')
    )
));