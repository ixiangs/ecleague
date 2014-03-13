<?php
namespace System;

use Toys\Orm;

class AreaModel extends Orm\ModelBase{

	const TABLE_NAME = '{t}system_area';

	protected function getMetadata(){
		return array(
			'table'=>AreaModel::TABLE_NAME,
			'properties'=>array(
		    Orm\IntegerProperty::create('id')->setPrimaryKey(true),
		    Orm\IntegerProperty::create('parent_id'),
		    Orm\StringProperty::create('name')->setNullable(false),
		    Orm\StringProperty::create('phone_code')
			)
		);
	}
}