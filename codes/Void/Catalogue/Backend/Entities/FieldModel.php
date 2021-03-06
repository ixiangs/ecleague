<?php
namespace Ixiangs\Entities;

use Toy\Orm;

class FieldModel extends Orm\Model
{
    static public function find()
    {
        return parent::find(false)
            ->select(static::propertiesToFields())
            ->select(AttributeModel::propertiesToFields(null, 'id'))
            ->join(AttributeModel::propertyToField('id'), static::propertyToField('attribute_id'));
    }
}

FieldModel::registerMetadata(array(
    'table' => Constant::TABLE_FIELD,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\IntegerProperty::create('entity_id')->setNullable(false),
        Orm\IntegerProperty::create('attribute_id')->setNullable(false),
        Orm\BooleanProperty::create('required')->setNullable(false)->setDefaultValue(false),
        Orm\BooleanProperty::create('indexable')->setNullable(false)->setDefaultValue(false),
        Orm\BooleanProperty::create('primary_key')->setNullable(false)->setDefaultValue(false),
        Orm\BooleanProperty::create('insertable')->setNullable(false)->setDefaultValue(true),
        Orm\BooleanProperty::create('updateable')->setNullable(false)->setDefaultValue(true),
        Orm\BooleanProperty::create('auto_increment')->setNullable(false)->setDefaultValue(false)
    )
));