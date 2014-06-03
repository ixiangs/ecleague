<?php
namespace Ixiangs\Office;

use Ixiangs\Entities\Helper;
use Toy\Orm;

class DepartmentModel extends Orm\Model
{

}

DepartmentModel::registerMetadata(Helper::getModelMetadata('Ixiangs\Office\DepartmentModel'));
//DepartmentModel::registerMetadata(array(
//    'table' => Constant::TABLE_DEPARTMENT,
//    'properties' => array(
//        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
//        Orm\StringProperty::create('name'),
//        Orm\StringProperty::create('memo')
//    ),
//    'relations' => array(
//        Orm\Relation::childrenRelation('staffs', '\Ixiangs\Office\StaffModel', 'entity_id')
//    )
//));