<?php
namespace Organization;

use Toy\Orm;

class DepartmentModel extends Orm\ModelBase{

	const TABLE_NAME = '{t}organization_department';
	
	public function getChildDepartments(){
		return self::find(array('parent_id =', $this->getId()));
	}
	
	public function getCompany(){
		return CompanyModel::load($this->getCompanyId());
	}
	
	protected function afterDelete($db){
		$db->update(EmployeeModel::TABLE_NAME, array('department_id'=>0), array(array('department_id =', $this->getId())));
	}

	protected function getMetadata(){
		return array(
			'table'=>self::TABLE_NAME,
			'properties'=>array(
		    Orm\IntegerProperty::create('id') -> setAutoIncrement(true) ->setPrimaryKey(true),
		    Orm\IntegerProperty::create('parent_id'),
		    Orm\IntegerProperty::create('company_id'),
		    Orm\StringProperty::create('name') -> setNullable(false)
			)
		);
	}
	
	public static function getDepartmentOptionGroups(){
		$companies = CompanyModel::find()->execute()->getModelArray();
		$departments = DepartmentModel::find()->execute()->getModelArray();
		$result = array();
		foreach($companies as $c){
			$item = array('label'=>$c->getName(), 'opitons'=>array());
			foreach($departments as $d){
				if($d->getCompanyId() == $c->getId()){
					$item['options'][$d->getId()] = $d->getName();
				}
			}
			$result[] = $item;
		}
		return $result;
	}
}