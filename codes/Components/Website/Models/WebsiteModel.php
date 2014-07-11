<?php
namespace Components\Website\Models;

use Toy\Orm, Toy\Orm\Db;
use Components\System;
use Components\Website\Constant;

class WebsiteModel extends Orm\Model
{

}

WebsiteModel::registerMetadata(array(
    'table' => Constant::TABLE_WEBSITE,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('account_id')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('title')->setNullable(false)
    )
));