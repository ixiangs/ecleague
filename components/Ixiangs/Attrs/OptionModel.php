<?php
namespace Ixiangs\Attrs;

use Toy\Orm;

class OptionModel extends Orm\Model
{

}

OptionModel::register(array(
    'table' => Constant::TABLE_OPTION,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('attribute_id')->setNullable(false),
        Orm\StringProperty::create('label')->setNullable(false),
        Orm\IntegerProperty::create('value')->setNullable(false)
    )
));