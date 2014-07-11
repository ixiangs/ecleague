<?php
namespace Components\Website\Models;

use Toy\Orm, Toy\Orm\Db;
use Components\System;
use Components\Website\Constant;

class MenuTypeModel extends Orm\Model
{

}

MenuTypeModel::registerMetadata(array(
    'table' => Constant::TABLE_MENU_TYPE,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('component_id')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('form_path')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('name')->setNullable(false)
    )
));