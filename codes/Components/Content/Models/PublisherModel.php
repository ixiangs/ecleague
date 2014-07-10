<?php
namespace Components\Content\Models;

use Toy\Orm;
use Components\Content\Constant;

class PublisherModel extends Orm\Model
{

    protected function afterInsert($db)
    {
        FileUtil::createDirectory(ASSET_PATH . 'content' . DS . $this->getId());
    }

    protected function afterDelete()
    {
        FileUtil::deleteDirectory(ASSET_PATH . 'content' . DS . $this->getId());
    }
}

PublisherModel::registerMetadata(array(
    'table' => Constant::TABLE_PUBLISHER,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('account_id')->setNullable(false),
        Orm\StringProperty::create('component_id')->setNullable(false),
        Orm\IntegerProperty::create('association_id')->setNullable(false),
        Orm\IntegerProperty::create('association_type')->setNullable(false),
        Orm\StringProperty::create('name')->setNullable(false)
    )
));