<?php
namespace Organization;

use Toys\Orm;

class EmployeeModel extends Orm\ModelBase{

	const TABLE_NAME = '{t}organization_employee';
	
	public function getBoss(){
		if(!empty($this->boss_id)){
			return self::find(array('boss_id ='=>$this->getId()))->execute()->getFirstModel();
		}
		return null;
	}
	
	public function getDepartment(){
		if(!empty($this->department_id)){
			return DepartmentModel::load(getDepartmentId());
		}
		return null;
	}	
	
	public function getPosition(){
		if(!empty($this->position_id)){
			return PositionModel::load(getPositionId());
		}
		return null;
	}		
	
	public function getCompany(){
		if(!empty($this->company_id)){
			return CompanyModel::load(getCompanyId());
		}
		return null;
	}		

	protected function getMetadata(){
		return array(
			'table'=>self::TABLE_NAME,
			'properties'=>array(
		    Orm\IntegerProperty::create('id') -> setAutoIncrement(true) ->setPrimaryKey(true),
		    Orm\IntegerProperty::create('boss_id')->setDefaultValue(0),
		    Orm\IntegerProperty::create('department_id') -> setNullable(false),
		    Orm\IntegerProperty::create('company_id') -> setNullable(false),
		    Orm\StringProperty::create('position_id') -> setNullable(false),
		    Orm\StringProperty::create('chinese_name') -> setNullable(false),
				Orm\StringProperty::create('english_name') -> setNullable(false),
				Orm\IntegerProperty::create('gender') -> setNullable(false),
				Orm\StringProperty::create('email') -> setNullable(false),
				Orm\StringProperty::create('mobile'),
				Orm\StringProperty::create('phone'),
				Orm\StringProperty::create('address'),
				Orm\StringProperty::create('responsibilities')
			)
		);
	}
}