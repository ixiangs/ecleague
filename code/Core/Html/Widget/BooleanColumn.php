<?php
namespace Core\Html\Widget;

class BooleanColumn extends GridColumn
{

    private $_fieldName = '';
    private $_trueText = '';
    private $_falseText = '';

    public function __construct()
    {
        parent::__construct();
        $this->getCell()->addChild(new Element('span'));
    }

    public function setFieldName($value)
    {
        $this->_fieldName = $value;
        return $this;
    }

    public function getFieldName()
    {
        return $this->_fieldName;
    }

    public function setTrueText($value)
    {
        $this->_trueText = $value;
        return $this;
    }

    public function getTrueText()
    {
        return $this->_trueText;
    }

    public function setFalseText($value)
    {
        $this->_falseText = $value;
        return $this;
    }

    public function getFalseText()
    {
        return $this->_falseText;
    }

    public function renderCell($row, $index)
    {
        if ($row[$this->_fieldName]) {
            $this->getCell()->getChild(0)->setAttribute(array(
                'class' => 'text-success',
                'text' => $this->_trueText
            ));
        } else {
            $this->getCell()->getChild(0)->setAttribute(array(
                'class' => 'text-danger',
                'text' => $this->_falseText
            ));
        }
        return parent::renderCell($row, $index);
    }
}