<?php
namespace Void\Core;

use Void\Core\Constant;
use Toy\Orm;

class ComponentModel extends Orm\Model
{

}

ComponentModel::registerMetadata(array(
    'table' => VOID_CORE_TABLE_COMPONENT,
    'properties' => array(
        Orm\StringProperty::create('id')->setPrimaryKey(true),
        Orm\StringProperty::create('code')->setNullable(false)->setUnique(true),
        Orm\StringProperty::create('name'),
        Orm\StringProperty::create('author'),
        Orm\StringProperty::create('website'),
        Orm\StringProperty::create('version'),
        Orm\StringProperty::create('description'),
        Orm\BooleanProperty::create('enabled')
    )
));