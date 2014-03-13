<?php

use Toys\Joy;
use Toys\Unit\TestCase;

class OrganisationTestCase extends TestCase {

	public function testCompanyModel(){
		$m = new \Organization\CompanyModel();
		$b = $m -> setName('company1') -> setParentId(0) -> insert();
		$this -> assertTrue($b);
		$this -> assertNotEmpty($m -> id);	
	}
}
