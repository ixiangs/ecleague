<?php
namespace Components\Content\Models;

use Toy\Orm;
use Components\Content\Constant;

class CategoryModel extends Orm\Model
{

}

CategoryModel::registerMetadata(array(
    'table' => Constant::TABLE_CATEGORY,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('parent_id')->setNullable(false),
        Orm\IntegerProperty::create('publisher_id')->setNullable(false),
        Orm\IntegerProperty::create('creator_id')->setNullable(false),
        Orm\StringProperty::create('name')->setNullable(false)
    )
));