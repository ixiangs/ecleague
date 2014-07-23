<?php
namespace Void\Weiweb;

use Toy\Orm, Toy\Orm\Db;
use Void\System;

class MenuModel extends Orm\Model
{

}

MenuModel::registerMetadata(array(
    'table' => Constant::TABLE_MENU,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('website_id')->setNullable(false)->setUpdateable(false),
        Orm\IntegerProperty::create('account_id')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('type_id')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('parent_id')->setNullable(false)->setDefaultValue(0),
        Orm\StringProperty::create('title')->setNullable(false),
        Orm\StringProperty::create('link')->setNullable(false),
        Orm\StringProperty::create('icon'),
        Orm\IntegerProperty::create('status')->setNullable(false)->setDefaultValue(true)
    )
));