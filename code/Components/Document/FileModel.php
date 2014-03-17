<?php
namespace Fs;

use Toy\Orm;

class FileModel extends Orm\ModelBase{

	const TABLE_NAME = '{t}document_file';

	protected function getMetadata(){
		return array(
			'table'=>self::TABLE_NAME,
			'properties'=>array(
		    Orm\StringProperty::create('id') ->setPrimaryKey(true),
		    Orm\StringProperty::create('path') -> setNullable(false),
		    Orm\IntegerProperty::create('is_directory') -> setNullable(false),
		    Orm\IntegerProperty::create('size') -> setNullable(false),
		    Orm\IntegerProperty::create('creator_id'),
		    Orm\IntegerProperty::create('owner_id'),
		    Orm\IntegerProperty::create('last_modified_time')
			)
		);
	}
}