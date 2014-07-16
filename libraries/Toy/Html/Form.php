<?php
namespace Toy\Html;

class Form extends Element
{

    protected $fields = array();
    protected $hiddens = array();

    public function __construct($id = 'form1', $method = 'post')
    {
        parent::__construct('form', array('class' => 'form-horizontal', 'id' => $id, 'method' => $method, 'data-validate' => 'true'));
    }

    public function getHiddens()
    {
        return $this->hiddens;
    }

    public function newField($label, $required = false, $input = null)
    {
        $res = new FormField($label);
        $res->setInput($input)->setRequired($required);
        $this->addField($res);
        return $res;
    }

    public function addField($field)
    {
        $this->fields[] = $field;
        return $this;
    }

    public function addStaticField($label, $value = null)
    {
        $f = new StaticField($label);
        $f->getInput()->setAttribute(array('text' => $value));
        $this->addField($f);
    }

    public function addHidden($id, $name, $value = null)
    {
        $f = new Element('input', array('type' => 'hidden', 'id' => $id, 'name' => $name, 'value' => $value));
        $this->hiddens[] = $f;
        return $f;
    }

    public function renderInner()
    {
        $res = array();
        foreach ($this->fields as $f) {
            $res[] = $f->render();
        }
        foreach ($this->hiddens as $f) {
            $res[] = $f->render();
        }
        return implode('', $res);
    }
}
