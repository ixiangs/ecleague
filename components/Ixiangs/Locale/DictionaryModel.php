<?php
namespace Ixiangs\Locale;

use Toy\Orm;

class DictionaryModel extends Orm\Model{

    const TABLE_NAME = '{t}locale_dictionary';
}

Orm\Model::register('Ixiangs\Locale\DictionaryModel', array(
    'table'=>DictionaryModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('language_id')->setNullable(false),
        Orm\StringProperty::create('code')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('label')->setNullable(false)
    )
));