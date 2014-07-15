<?php
namespace Void\Weiweb;

use Toy\Orm, Toy\Orm\Db;
use Void\System;

class WebsiteModel extends Orm\Model
{

}

WebsiteModel::registerMetadata(array(
    'table' => Constant::TABLE_MENU,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('account_id')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('title')->setNullable(false)
    )
));