<?php
namespace Components\Content\Models;

use Toy\Orm;
use Components\Content\Constant;

class PublisherModel extends Orm\Model
{

}

PublisherModel::registerMetadata(array(
    'table' => Constant::TABLE_PUBLISHER,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('user_id')->setNullable(false),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\IntegerProperty::create('association_id')->setNullable(false),
        Orm\IntegerProperty::create('association_type')->setNullable(false),
        Orm\StringProperty::create('name')->setNullable(false)
    )
));