<?php
namespace Core\Dass\Model;

use Toy\Orm;

class AttributeGroupModel extends Orm\Model{

    const TABLE_NAME = '{t}dass_attribute_group';

}

Orm\Model::register('Core\Dass\Model\AttributeGroupModel', array(
    'table'=>AttributeGroupModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setNullable(false)->setUnique(true),
        Orm\BooleanProperty::create('enabled')->setNullable(false),
        Orm\SerializeProperty::create('names')->setNullable(false),
        Orm\ListProperty::create('attribute_ids')->setNullable(false)
    )
));