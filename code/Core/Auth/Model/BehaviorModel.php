<?php
namespace Core\Auth\Model;

use Toy\Orm;

class BehaviorModel extends Orm\Model
{
    const TABLE_NAME = '{t}auth_behavior';
}

Orm\Entity::register('Core\Auth\Model\BehaviorModel', array(
    'table' => BehaviorModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true)->setUpdateable(false),
        Orm\StringProperty::create('name'),
        Orm\StringProperty::create('url'),
        Orm\BooleanProperty::create('enabled')
    )
));