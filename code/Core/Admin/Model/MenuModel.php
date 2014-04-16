<?php
namespace Core\Admin\Model;

use Toy\Orm;

class MenuModel extends Orm\Model
{
    const TABLE_NAME = '{t}admin_menu';

    protected function beforeInsert($db)
    {
        if($this->isEmptyData('parent_id')){
            $this->setData('parent_id', 0);
        }
        return parent::beforeInsert($db);
    }

    protected function beforeUpdate($db)
    {
        return $this->beforeInsert($db);
    }
}

Orm\Model::register('Core\Admin\Model\MenuModel', array(
    'table' => MenuModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('component_id'),
        Orm\IntegerProperty::create('parent_id')->setNullable(true),
        Orm\SerializeProperty::create('names')->setNullable(false),
        Orm\StringProperty::create('url'),
        Orm\BooleanProperty::create('enabled')->setNullable(false)
    )
));