<?php
namespace Core\Dass\Model;

use Toy\Orm;

class AttributeSetModel extends Orm\Model{

    const TABLE_NAME = '{t}dass_attribute_set';

}

Orm\Model::register('Core\Dass\Model\AttributeSetModel', array(
    'table'=>AttributeSetModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setNullable(false)->setUnique(true),
        Orm\BooleanProperty::create('enabled')->setNullable(false),
        Orm\SerializeProperty::create('names')->setNullable(false),
        Orm\ListProperty::create('group_ids')->setNullable(false)
    )
));