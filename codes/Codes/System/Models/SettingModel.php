<?php
/**
 * Created by PhpStorm.
 * User: ronald.xian
 * Date: 14-6-3
 * Time: 上午11:36
 */

namespace Codes\System\Models;

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