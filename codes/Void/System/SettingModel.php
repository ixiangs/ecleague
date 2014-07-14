<?php
namespace Void\System;

use Void\System\Constant;
use Toy\Orm;

class SettingModel extends Orm\Model{

}

SettingModel::registerMetadata(array(
    'table' => Constant::TABLE_SETTING,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('website_title')->setNullable(false),
        Orm\StringProperty::create('website_description')->setNullable(false),
        Orm\BooleanProperty::create('offline')->setDefaultValue(0)
    )
));