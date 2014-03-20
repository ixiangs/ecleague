<?php
namespace Core\Locale\Model;

use Toy\Orm;

class DictionaryModel extends Orm\Model{

    const TABLE_NAME = '{t}locale_dictionary';
}

Orm\Entity::register('Core\Locale\Model\DictionaryModel', array(
    'table'=>DictionaryModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('language_id')->setNullable(false),
        Orm\StringProperty::create('code')->setNullable(false),
        Orm\StringProperty::create('label')->setNullable(false)
    )
));