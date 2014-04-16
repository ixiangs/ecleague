<?php
namespace Core\Attrs\Model;

use Toy\Orm;

class AttributeOptionModel extends Orm\Model
{

    const TABLE_NAME = '{t}attrs_attribute_option';

}

Orm\Model::register('Core\Attrs\Model\AttributeOptionModel', array(
    'table' => AttributeOptionModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('attribute_id')->setNullable(false),
        Orm\StringProperty::create('value')->setNullable(false),
        Orm\SerializeProperty::create('labels')->setNullable(false)
    )
));