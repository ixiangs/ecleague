<?php
namespace Core\Dass\Model;

use Toy\Orm;

class AttributeVersionModel extends Orm\Model
{
    const TABLE_NAME = '{t}dass_attribute_version';

}

Orm\Model::register('Core\Dass\Model\AttributeVersionModel', array(
    'table' => AttributeVersionModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('version_id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('main_id')->setNullable(false),
        Orm\IntegerProperty::create('language_id')->setNullable(false),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\StringProperty::create('display_label')->setNullable(false),
        Orm\StringProperty::create('form_label')->setNullable(false)
    )
));