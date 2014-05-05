<?php
namespace Core\User\Model;

use Toy\Orm;

class AccountModel extends Orm\Model{

    const TABLE_NAME = '{t}user_account';

}

Orm\Model::register('Core\User\Model\AccountModel', array(
    'table'=>AccountModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('username')->setNullable(false)->setUnique(true)->setUpdateable(false),
        Orm\IntegerProperty::create('password')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('first_name')->setNullable(false),
        Orm\StringProperty::create('last_name')->setNullable(false),
        Orm\IntegerProperty::create('gender')->setNullable(false),
        Orm\EmailProperty::create('email')->setNullable(false),
        Orm\StringProperty::create('mobile')
    )
));