<?php
namespace Core\Auth\Model;

use Toy\Orm;

class RoleModel extends Orm\Model{

	const TABLE_NAME = '{t}auth_role';

	public function getBehaviors(){
		return BehaviorModel::find()->in('id', $this->getBehaviorIds());
	}
}

Orm\Model::register('Core\Auth\Model\RoleModel', array(
    'table'=>RoleModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true),
        Orm\StringProperty::create('name'),
        Orm\ArrayProperty::create('behavior_ids'),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)
    )
));