<?php
namespace Core\Catalogue\Model;

use Toy\Orm;

class ProductModel extends Orm\Model
{

    const TABLE_NAME = '{t}catalogue_product';

}

Orm\Model::register('Core\Catalogue\Model\ProductModel', array(
    'table' => ProductModel::TABLE_NAME,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('sku')->setNullable(false),
        Orm\SerializeProperty::create('name')->setNullable(false),
        Orm\SerializeProperty::create('description')->setNullable(false),
        Orm\SerializeProperty::create('pictures')->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setNullable(false)
    )
));