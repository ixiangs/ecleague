<?php
namespace Core\Dass\Model;

use Toy\Orm;
use Toy\Orm\Versioning;

class AttributeModel extends Versioning\Model{

    const TABLE_NAME = '{t}dass_attribute';

}

Versioning\Entity::register('Core\Dass\Model\AttributeModel', array(
    'table'=>AttributeSetModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setNullable(false),
        Orm\StringProperty::create('display_label')->setNullable(false),
        Orm\StringProperty::create('from_label')->setNullable(false),
        Orm\BooleanProperty::create('indexable')->setNullable(false),
        Orm\StringProperty::create('data_type')->setNullable(false),
        Orm\StringProperty::create('input_type')->setNullable(false)
    )
));