<?php
namespace Core\Attrs\Model;

use Toy\Orm;

class AttributeGroupModel extends Orm\Model{

    const TABLE_NAME = '{t}attrs_attribute_group';

}

Orm\Model::register('Core\Attrs\Model\AttributeGroupModel', array(
    'table'=>AttributeGroupModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setNullable(false)->setUnique(true),
        Orm\BooleanProperty::create('enabled')->setNullable(false),
        Orm\SerializeProperty::create('name')->setNullable(false),
        Orm\ListProperty::create('attribute_ids')->setNullable(false),
        Orm\StringProperty::create('component_code')->setNullable(false),
        Orm\SerializeProperty::create('memo')->setNullable(false)
    )
));