<?php
namespace Toy\Orm;

use Toy\Validation\Tester;

class IntegerProperty extends BaseProperty
{

    private $_minValue = null;
    private $_maxValue = null;

    public function getMinValue()
    {
        return $this->_minValue;
    }

    public function getMaxValue()
    {
        return $this->_maxValue;
    }

    public function setRangeValue($min, $max)
    {
        $this->_minValue = $min;
        $this->_maxValue = $max;
        return $this;
    }

    public function toDbValue($value)
    {
        if (is_null($value)) {
            return $this->getDefaultValue();
        }

        if (!is_int($value)) {
            return (int)$value;
        }

        return $value;
    }

    public function fromDbValue($value)
    {
        if (empty($value)) {
            return null;
        }

        if (!is_int($value)) {
            return (int)$value;
        }

        return $value;
    }

    public function validate($value)
    {
        $empty = is_null($value) || strlen($value) == 0;
        if ($empty) {
            return $this->getNullable();
        } else {
            if (!Tester::testInteger($value)) {
                return false;
            }

            if (!is_null($this->_minValue) && $value < $this->_minValue) {
                return false;
            }

            if (!is_null($this->_maxValue) && $value > $this->_maxValue) {
                return false;
            }

            return TRUE;
        }
    }
}
