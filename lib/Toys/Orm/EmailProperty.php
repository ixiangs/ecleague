<?php
namespace Toys\Orm;
use Toys\Validation\Tester;

class EmailProperty extends PropertyBase {
	public function validate($value) {
		$empty = is_null($value);
		if($empty){
			return $this->getNullable();
		}else{
			return Tester::testEmail($value);
		}
	}
}
