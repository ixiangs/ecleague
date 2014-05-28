<?php
namespace Ixiangs\Attrs;

use Toy\View\Html;
use Toy\Orm;
use Toy\Util\ArrayUtil;

class EntityModel extends Orm\Model
{

}

EntityModel::register(array(
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
        Orm\Relation::childrenRelation('groups', '\Ixiangs\Attrs\GroupModel', 'entity_id'),
        Orm\Relation::childrenRelation('fields', '\Ixiangs\Attrs\FieldModel', 'entity_id')
    )
));