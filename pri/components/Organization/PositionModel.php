<?php
namespace Organization;

use Toys\Orm;

class PositionModel extends Orm\ModelBase{

	const TABLE_NAME = '{t}organization_position';
	
	public function getDepartments(){
		return self::find(array('parent_id =', $this->getId()));
	}
	
	protected function afterDelete($db){
		$db->update(EmployeeModel::TABLE_NAME, array('position_id'=>0), array(array('position_id =', $this->getId())));
	}

	protected function getMetadata(){
		return array(
			'table'=>self::TABLE_NAME,
			'properties'=>array(
		    Orm\IntegerProperty::create('id') -> setAutoIncrement(true) ->setPrimaryKey(true),
		    Orm\IntegerProperty::create('parent_id')->setDefaultValue(0),
		    Orm\IntegerProperty::create('department_id') -> setNullable(false),
		    Orm\StringProperty::create('chinese_name') -> setNullable(false),
		    Orm\StringProperty::create('english_name') -> setNullable(false)
			)
		);
	}
}