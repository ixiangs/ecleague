<?php
namespace Toy\Orm;

use Toy\Validation\Tester;

class EmailProperty extends BaseProperty
{
    public function validate($value)
    {
        $empty = is_null($value);
        if ($empty) {
            return $this->getNullable();
        } else {
            return Tester::testEmail($value);
        }
    }
}
