<?php
namespace Components\Realty\Models;

use Toy\Orm, Toy\Orm\Db;
use Components\System;
use Components\Content\Models\PublisherModel;
use Components\Realty\Constant;

class UptownModel extends Orm\Model
{
    protected function afterInsert($db)
    {
        $cid = System\Helper::getComponentId('ixiangs_realty');
        PublisherModel::create(array(
            'user_id'=>$this->user_id,
            'component_id' => $cid,
            'association_type' => 1,
            'association_id' => $this->id,
            'name' => $this->name
        ))->insert($db);
    }

    protected function afterUpdate($db)
    {
        if ($this->propertyIsChanged('name')) {
            $cid = System\Helper::getComponentId('ixiangs_realty');
            Db\Helper::update(
                PublisherModel::getMetadata()->getTableName(),
                array('name' => $this->name))
                ->eq('component_id', $cid)
                ->eq('association_id', $this->id)
                ->execute($db);
        }
    }
}

UptownModel::registerMetadata(array(
    'table' => Constant::TABLE_UPTOWN,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('developer_id')->setNullable(false),
        Orm\IntegerProperty::create('user_id')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\StringProperty::create('address')->setNullable(false)
    )
));