<?php
namespace Core\User\Model;

use Toy\Orm;

class ProfileModel extends Orm\Model{

    const TABLE_NAME = '{t}user_profile';

}

Orm\Model::register('Core\User\Model\ProfileModel', array(
    'table'=>ProfileModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('account_id')->setNullable(false)->setUnique(true),
        Orm\StringProperty::create('real_name')->setNullable(false),
        Orm\IntegerProperty::create('gender')->setNullable(false),
        Orm\SerializeProperty::create('names')->setNullable(false),
        Orm\ListProperty::create('group_ids')->setNullable(false)
    )
));