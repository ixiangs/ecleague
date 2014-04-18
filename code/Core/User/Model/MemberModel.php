<?php
namespace Core\User\Model;

use Toy\Orm;

class MemberModel extends Orm\Model{

    const TABLE_NAME = '{t}user_member';

}

Orm\Model::register('Core\User\Model\MemberModel', array(
    'table'=>MemberModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('account_id')->setNullable(false)->setUnique(true)->setUpdateable(false),
        Orm\StringProperty::create('first_name')->setNullable(false),
        Orm\StringProperty::create('last_name')->setNullable(false),
        Orm\IntegerProperty::create('gender')->setNullable(false),
        Orm\EmailProperty::create('email')->setNullable(false),
        Orm\StringProperty::create('mobile')
    )
));