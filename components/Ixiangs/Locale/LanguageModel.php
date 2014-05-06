<?php
namespace Ixiangs\Locale;

use Toy\Orm;

class LanguageModel extends Orm\Model{

    const TABLE_NAME = '{t}locale_language';

}

Orm\Model::register('Ixiangs\Locale\LanguageModel', array(
    'table'=>LanguageModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\IntegerProperty::create('timezone')->setNullable(false),
        Orm\StringProperty::create('currency_code')->setNullable(false),
        Orm\StringProperty::create('currency_symbol')->setNullable(false),
        Orm\StringProperty::create('short_date_format')->setNullable(false),
        Orm\StringProperty::create('long_date_format')->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)
    )
));