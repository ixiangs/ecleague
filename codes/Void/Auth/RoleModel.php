<?php
namespace Void\Auth;

use Toy\Orm;
use Void\Auth\Constant;

class RoleModel extends Orm\Model{

	public function getBehaviors(){
		return BehaviorModel::find()->in('id', $this->behavior_ids);
	}
}

RoleModel::registerMetadata(array(
    'table'=>Constant::TABLE_ROLE,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('code')->setUnique(true)->setUpdateable(false),
        Orm\StringProperty::create('name'),
        Orm\ListProperty::create('behavior_ids'),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)
    )
));