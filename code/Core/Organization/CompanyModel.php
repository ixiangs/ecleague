<?php
namespace Organization;

use Toy\Orm;

class CompanyModel extends Orm\ModelBase{

	const TABLE_NAME = '{t}organization_company';
	
	public function getChildCompanys(){
		return self::find(array('parent_id =', $this->getId()));
	}

	protected function getMetadata(){
		return array(
			'table'=>self::TABLE_NAME,
			'properties'=>array(
		    Orm\IntegerProperty::create('id') -> setAutoIncrement(true) ->setPrimaryKey(true),
		    Orm\IntegerProperty::create('parent_id'),
		    Orm\StringProperty::create('name') -> setNullable(false)
			)
		);
	}
}