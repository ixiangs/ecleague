<?php
namespace Void\Content;

use Toy\Orm;

class CategoryModel extends Orm\Model
{
    protected function afterDelete($db)
    {
        Orm\Db\Helper::update(Constant::TABLE_ARTICLE, array('category_id'=>0))
            ->eq('category_id', $this->id)
            ->execute($db);
    }
}

CategoryModel::registerMetadata(array(
    'table' => VOID_CONTENT_TABLE_CATEGORY,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('parent_id')->setNullable(false),
        Orm\IntegerProperty::create('account_id')->setNullable(false),
        Orm\StringProperty::create('name')->setNullable(false)
    )
));