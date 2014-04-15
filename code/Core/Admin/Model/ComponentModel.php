<?php
namespace Core\Admin\Model;

use Toy\Orm;

class ComponentModel extends Orm\Model
{
    const TABLE_NAME = '{t}admin_component';
}

Orm\Model::register('Core\Admin\Model\ComponentModel', array(
    'table' => ComponentModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('name'),
        Orm\StringProperty::create('author'),
        Orm\StringProperty::create('website'),
        Orm\StringProperty::create('version'),
        Orm\StringProperty::create('description'),
        Orm\BooleanProperty::create('enabled')
    )
));