<?php
namespace Ixiangs\System;

use Toy\Orm;

class DictionaryModel extends Orm\Model{

}

DictionaryModel::register(array(
    'table'=>Constant::TABLE_DICTIONARY,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('language_id')->setNullable(false),
        Orm\StringProperty::create('code')->setNullable(false),
        Orm\StringProperty::create('label')->setNullable(false)
    )
));