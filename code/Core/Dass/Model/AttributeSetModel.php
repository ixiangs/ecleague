<?php
namespace Core\Dass\Model;

use Toy\Orm;
use Toy\Orm\Versioning;

class AttributeSetModel extends Versioning\Model{

    const TABLE_NAME = '{t}dass_attribute_set';

}

Versioning\Entity::register('Core\Dass\Model\AttributeSetModel', array(
    'table'=>AttributeSetModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setNullable(false)->setUnique(true),
        Orm\StringProperty::create('label')->setNullable(false)
    )
));