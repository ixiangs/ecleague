<?php
namespace Components\System\Models;

use Toy\Orm;

class SettingModel {

}

SettingModel::registerMetadata(array(
    'table' => Constant::TABLE_SETTING,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('website_title')
    )
));