<?php
namespace Void\Weiweb;

use Toy\Orm, Toy\Orm\Db;
use Void\System;

class WebsiteModel extends Orm\Model
{

    public function getMenus(){
        return MenuModel::find()
            ->eq('website_id', $this->getId());
    }
}

WebsiteModel::registerMetadata(array(
    'table' => Constant::TABLE_WEBSITE,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('account_id')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('title')->setNullable(false),
        Orm\StringProperty::create('background_color'),
        Orm\StringProperty::create('background_image'),
        Orm\StringProperty::create('theme')->setNullable(false),
        Orm\IntegerProperty::create('status')->setNullable(false)->setDefaultValue(1)
    )
));