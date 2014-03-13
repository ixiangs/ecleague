<?php
namespace User;

use Toys\Orm;

class RoleModel extends Orm\ModelBase{

	const TABLE_NAME = '{t}user_role';
	
	public static function checkCode($code){
		$m = self::find(array('code =' => $code))->count() -> execute() -> getFirstValue();
		return $m > 0;			
	}	

	protected function getMetadata(){
		return array(
			'table'=>RoleModel::TABLE_NAME,
			'properties'=>array(
		    Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
		    Orm\StringProperty::create('code')->setUnique(true),
		    Orm\StringProperty::create('label'),
		    Orm\ArrayProperty::create('behavior_ids'),
		    Orm\BooleanProperty::create('enabled')->setDefaultValue(true)
			)
		);
	}

	public function getBehaviors(){
		return BehaviorModel::find()->andFilter('id in', $this->getBehaviorIds());
	}
}