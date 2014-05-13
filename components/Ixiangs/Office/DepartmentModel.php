<?php
namespace Ixiangs\Office;

use Toy\Orm;

class DepartmentModel extends Orm\Model{

}

DepartmentModel::register(array(
    'table'=>Constant::TABLE_DEPARTMENT,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('name'),
        Orm\StringProperty::create('description')
    ),
    'relations'=>array(
        new Orm\Relation('staffs', '\Ixiangs\Office\StaffModel', 'department_id')
    )
));