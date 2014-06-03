<?php
namespace Ixiangs\Entities;

use Toy\Orm;

class GroupModel extends Orm\Model
{

}

GroupModel::registerMetadata(array(
    'table' => Constant::TABLE_GROUP,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\IntegerProperty::create('entity_id')->setNullable(false),
        Orm\ListProperty::create('field_ids'),
        Orm\IntegerProperty::create('position')->setDefaultValue(99)
    )
));