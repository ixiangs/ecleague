<?php
namespace Core\Dass\Model;

use Toy\Orm;

class AttributeOptionModel extends Orm\Model
{

    const TABLE_NAME = '{t}dass_attribute_option';

}

Orm\Model::register('Core\Dass\Model\AttributeOptionModel', array(
    'table' => AttributeOptionModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('attribute_id')->setNullable(false),
        Orm\StringProperty::create('value')->setNullable(false),
        Orm\SerializeProperty::create('labels')->setNullable(false)
    )
));