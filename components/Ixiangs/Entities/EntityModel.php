<?php
namespace Ixiangs\Entities;

use Toy\View\Html;
use Toy\Orm;

class EntityModel extends Orm\Model
{

}

EntityModel::registerMetadata(array(
    'table' => Constant::TABLE_ENTITY,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\StringProperty::create('model')->setNullable(false),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\ListProperty::create('attribute_ids'),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)->setNullable(true),
        Orm\StringProperty::create('memo')
    ),
    'relations' => array(
        Orm\Relation::childrenRelation('groups', '\Ixiangs\Entities\GroupModel', 'entity_id'),
        Orm\Relation::childrenRelation('fields', '\Ixiangs\Entities\FieldModel', 'entity_id')
    )
));