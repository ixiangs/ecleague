<?php
namespace Ixiangs\Entities;

use Toy\Orm;
use Toy\Db;

class AttributeModel extends Orm\Model
{

}

AttributeModel::registerMetadata(array(
    'table' => Constant::TABLE_ATTRIBUTE,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\StringProperty::create('data_type')->setNullable(false),
        Orm\StringProperty::create('input_type')->setNullable(false),
        Orm\StringProperty::create('input_id'),
        Orm\StringProperty::create('input_name'),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)->setNullable(false),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\StringProperty::create('label')->setNullable(false),
        Orm\StringProperty::create('memo')->setNullable(false),
        Orm\SerializeProperty::create('input_setting')
    ),
    'relations'=>array(
        Orm\Relation::childrenRelation('options', '\Ixiangs\Entities\OptionModel', 'attribute_id')
    )
));