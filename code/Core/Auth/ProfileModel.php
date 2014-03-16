<?php
namespace User;

use Toy\Orm;

class ProfileModel extends Orm\ModelBase{

	const TABLE_NAME = '{t}user_profile';

	protected function getMetadata(){
		return array(
			'table'=>self::TABLE_NAME,
			'properties'=>array(
		    Orm\IntegerProperty::create('account_id') ->setPrimaryKey(true),
		    Orm\StringProperty::create('chinese_name'),
				Orm\StringProperty::create('english_name'),
				Orm\IntegerProperty::create('gender') -> setDefaultValue(0),
				Orm\StringProperty::create('work_email'),
				Orm\StringProperty::create('work_phone'),
				Orm\StringProperty::create('personal_email'),
				Orm\StringProperty::create('personal_phone'),				
				Orm\StringProperty::create('mobile')
			)
		);
	}
}