<?php
namespace Toy\Validation;

class Validator
{

    private $_data = array();

    public function setData(array $value)
    {
        $this->_data = $value;
        return $this;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function getValue($name)
    {
        if (isset($this->_data[$name])) {
            return $this->_data[$name];
        }
        return '';
    }

    public function notEmpty($name, $default = false)
    {
        if (Tester::notEmpty($this->getValue($name))) {
            return $this->getValue($name);
        }
        return $default;
    }

    public function isInteger($name, $default = false)
    {
        if (Tester::isInteger($this->getValue($name))) {
            return $this->getValue($name);
        }
        return $default;
    }

    public function isDigit($name, $default = false)
    {
        if (Tester::isDigit($this->getValue($name))) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function isNumeric($name, $default = false)
    {
        if (Tester::isNumeric($this->getValue($name))) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function isEmail($name, $default = false)
    {
        if (Tester::isEmail($this->getValue($name))) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function isDateTime($name, $default = false)
    {
        if (Tester::isDateTime($this->getValue($name))) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function isAlpha($name, $default = false)
    {
        if (Tester::isAlpha($this->getValue($name))) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function isAlphanum($name, $default = false)
    {
        if (Tester::isAlphanum($this->getValue($name))) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function maxLength($name, $length, $default = false)
    {
        if (Tester::maxLength($this->getValue($name), $length)) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function minLength($name, $length, $default = false)
    {
        if (Tester::minLength($this->getValue($name), $length)) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function rangeLength($name, $min, $max, $default = false)
    {
        if (Tester::rangeLength($this->getValue($name), $min, $max)) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function maxValue($name, $max, $default = false)
    {
        if (Tester::maxValue($this->getValue($name), $max)) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function minValue($name, $min, $default = false)
    {
        if (Tester::minValue($this->getValue($name), $min)) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function rangeValue($name, $min, $max, $default = false)
    {
        if (Tester::minValue($this->rangeValue($name), $min, $max)) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function testRegex($name, $pattern, $default = false)
    {
        if (Tester::testRegex($this->rangeValue($name))) {
            return $this->getValue($name);

        }
        return $default;
    }

    public function isIn($name, array $values, $default = false)
    {
        if (in_array($this->getValue($name), $values)) {
            return $this->getValue($name);

        }
        return $default;
    }

}
