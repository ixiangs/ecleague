<?php
namespace Void\Auth;

use Toy\Orm;
use Void\Auth\Constant;

class GroupModel extends Orm\Model
{

}

GroupModel::registerMetadata(array(
    'table' => Constant::TABLE_GROUP,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true)->setUpdateable(false),
        Orm\StringProperty::create('name')->setNullable(false)
    )
));