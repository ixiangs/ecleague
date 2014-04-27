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
//        Orm\StringProperty::create('code')->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)->setNullable(false),
        Orm\SerializeProperty::create('name')->setNullable(false),
        Orm\ListProperty::create('attribute_ids'),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\SerializeProperty::create('memo')
    )
));