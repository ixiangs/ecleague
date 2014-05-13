<?php
namespace Ixiangs\Office;

use Toy\Orm;

class StaffModel extends Orm\Model{

}

StaffModel::register(array(
    'table'=>Constant::TABLE_STAFF,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('account_id')->setAutoIncrement(true)->setUpdateable(false),
        Orm\IntegerProperty::create('position_id')->setNullable(false),
        Orm\IntegerProperty::create('department_id')->setNullable(null),
        Orm\StringProperty::create('name'),
        Orm\StringProperty::create('gender'),
        Orm\StringProperty::create('birthdate'),
        Orm\BooleanProperty::create('marital'),
        Orm\StringProperty::create('introduction'),
        Orm\StringProperty::create('photo'),
        Orm\StringProperty::create('telephone'),
        Orm\StringProperty::create('mobile')->setNullable(false),
        Orm\StringProperty::create('personal_email')->setNullable(false),
        Orm\StringProperty::create('work_email')->setNullable(false),
        Orm\StringProperty::create('qq'),
        Orm\StringProperty::create('msn'),
        Orm\StringProperty::create('skype'),
        Orm\StringProperty::create('address'),
    )
));