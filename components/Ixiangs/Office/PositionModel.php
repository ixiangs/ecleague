<?php
namespace Ixiangs\Office;

use Toy\Orm;

class PositionModel extends Orm\Model{

}

PositionModel::registerMetadata(array(
    'table'=>Constant::TABLE_POSITION,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('parent_id')->setDefaultValue(null),
        Orm\StringProperty::create('name'),
        Orm\StringProperty::create('description')
    ),
    'relations'=>array(
        new Orm\Relation('staffs', '\Ixiangs\Office\StaffModel', 'position_id'),
        new Orm\Relation('child_positions', '\Ixiangs\Office\PositionModel', 'parent_id'),
        new Orm\Relation('parent_position', '\Ixiangs\Office\PositionModel', 'id', 'parent_id')
    )
));