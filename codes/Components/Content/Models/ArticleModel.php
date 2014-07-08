<?php
namespace Components\Content\Models;

use Toy\Orm;
use Components\Content\Constant;

class ArticleModel extends Orm\Model
{

}

ArticleModel::registerMetadata(array(
    'table' => Constant::TABLE_ARTICLE,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('category_id')->setNullable(false),
        Orm\IntegerProperty::create('publisher_id')->setNullable(false),
        Orm\IntegerProperty::create('editor_id')->setNullable(false),
        Orm\StringProperty::create('title')->setNullable(false),
        Orm\StringProperty::create('content')->setNullable(false),
        Orm\IntegerProperty::create('start_time')->setDefaultValue(0),
        Orm\IntegerProperty::create('end_time')->setDefaultValue(0)
    )
));