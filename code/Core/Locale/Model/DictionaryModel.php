<?php
namespace Core\Locale\Model;

use Toy\Orm\Model;

class DictionaryModel extends Model{

    const TABLE_NAME = '{t}locale_dictionary';
}

Orm\Entity::register('Core\Locale\Model\DictionaryModel', array(
    'table'=>RoleModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('language_id')->setNullable(false),
        Orm\IntegerProperty::create('component_id')->setNullable(false)->setDefaultValue(1),
        Orm\StringProperty::create('code')->setNullable(false),
        Orm\StringProperty::create('label')->setNullable(false)
    )
));