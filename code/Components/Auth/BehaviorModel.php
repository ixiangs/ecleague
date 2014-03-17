<?php
namespace Components\Auth;

use Toy\Orm;

class BehaviorModel extends Orm\Model
{

    const TABLE_NAME = '{t}auth_behavior';

    public static function checkCode($code)
    {
        $m = self::find(array('code =' => $code))->count()->execute()->getFirstValue();
        return $m > 0;
    }
}

Orm\Entity::register('Components\Auth\BehaviorModel', array(
    'table' => BehaviorModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true)->setUpdateable(false),
        Orm\StringProperty::create('name'),
        Orm\StringProperty::create('url'),
        Orm\BooleanProperty::create('enabled')
    )
));