<?php
namespace Void\Realty;

use Toy\Orm, Toy\Orm\Db;

class UptownModel extends Orm\Model
{
    protected function afterInsert($db)
    {
        PublisherModel::create(array(
            'account_id'=>$this->account_id,
            'component_id' => 'ixiangs_realty',
            'association_type' => 1,
            'association_id' => $this->id,
            'name' => $this->name
        ))->insert($db);
    }

    protected function afterUpdate($db)
    {
        if ($this->propertyIsChanged('name')) {
            Db\Helper::update(
                PublisherModel::getMetadata()->getTableName(),
                array('name' => $this->name))
                ->eq('component_id', 'ixiangs_realty')
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
        Orm\IntegerProperty::create('account_id')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\StringProperty::create('address')->setNullable(false)
    )
));