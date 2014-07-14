<?php
namespace Void\Weiweb;

use Toy\Orm, Toy\Orm\Db;
use Void\System;
use Void\Weiweb\Constant;

class MenuModel extends Orm\Model
{

}

MenuModel::registerMetadata(array(
    'table' => Constant::TABLE_MENU,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('component_id')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('form_path')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('name')->setNullable(false)
    )
));