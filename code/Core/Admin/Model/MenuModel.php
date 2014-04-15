<?php
namespace Core\Admin\Model;

use Toy\Orm;
use Toy\Data\Db;

class MenuModel extends Orm\Model
{
    const TABLE_NAME = '{t}admin_menu';
}

Orm\Model::register('Core\Admin\Model\MenuModel', array(
    'table' => MenuModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('parent_id'),
        Orm\IntegerProperty::create('component_id')->setUpdateable(false),
        Orm\SerializeProperty::create('names'),
        Orm\StringProperty::create('url'),
        Orm\BooleanProperty::create('enabled')
    )
));