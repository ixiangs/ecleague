<?php
namespace Ixiangs\System;

use Toy\Orm;

class LanguageModel extends Orm\Model{

}

LanguageModel::register(array(
    'table'=>Constant::TABLE_LANGUAGE,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true)->setUpdateable(false),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\IntegerProperty::create('timezone')->setNullable(false),
        Orm\StringProperty::create('currency_code')->setNullable(false),
        Orm\StringProperty::create('currency_symbol')->setNullable(false),
        Orm\StringProperty::create('short_date_format')->setNullable(false),
        Orm\StringProperty::create('long_date_format')->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)
    )
));