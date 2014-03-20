<?php
namespace Core\Locale\Model;

use Toy\Orm;

class LanguageModel extends Orm\Model{

    const TABLE_NAME = '{t}locale_language';

}

Orm\Entity::register('Core\Locale\Model\LanguageModel', array(
    'table'=>LanguageModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true),
        Orm\StringProperty::create('name')->setUnique(true),
        Orm\ArrayProperty::create('timezone'),
        Orm\ArrayProperty::create('short_date_format'),
        Orm\ArrayProperty::create('long_date_format'),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)
    )
));