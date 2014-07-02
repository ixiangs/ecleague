<?php
namespace Toy\Html;

class Form extends Element
{

    protected $fields = array();
    protected $hiddens = array();

    public function __construct($id = 'form1', $method = 'post')
    {
        parent::__construct('form', array('class' => 'form', 'id' => $id, 'method' => $method, 'data-validate' => 'true'));
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
//
//    public function addInputField($type, $label, $id, $name, $value = null)
//    {
//        $this->newField($label)
//        $f = new InputField($type, $label);
//        $f->getInput()->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value));
//        $this->addField($f);
//        if ($type == 'file') {
//            $this->setAttribute('enctype', 'multipart/form-data');
//        }
//        return $f;
//    }
//
//    public function addTextareaField($label, $id, $name, $value = null)
//    {
//        $f = new TextareaField($label);
//        $f->getInput()->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value));
//        $this->addField($f);
//        return $f;
//    }
//
//    public function addSelectField($options, $label, $id, $name, $value = null)
//    {
//        $f = new SelectField($label);
//        $f->getInput()
//            ->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value))
//            ->setOptions($options);
//        $this->addField($f);
//        return $f;
//    }
//
//    public function addTreeSelectField($options, $label, $id, $name, $value = null)
//    {
//        $f = new TreeSelectField($label);
//        $f->getInput()
//            ->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value))
//            ->setOptions($options);
//        $this->addField($f);
//        return $f;
//    }
//
//    public function addCheckboxListField($options, $label, $id, $name, $value = null)
//    {
//        $f = new OptionListField($label, true);
//        $f->getInput()
//            ->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value))
//            ->setOptions($options);
//        $this->addField($f);
//        return $f;
//    }
//
//    public function addRadioButtonListField($options, $label, $id, $name, $value = null)
//    {
//        $f = new OptionListField($label, false);
//        $f->getInput()
//            ->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value))
//            ->setOptions($options);
//        $this->addField($f);
//        return $f;
//    }
//
//    public function addInputGroupField($type, $label, $id, $name, $value = null)
//    {
//        $f = new InputGroupField($type, $label);
//        $f->getInput()->getInput()->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value));
//        $this->addField($f);
//        return $f;
//    }
//
//    public function addCustomField($renderer)
//    {
//        $f = new CustomField();
//        $f->setRenderer($renderer);
//        $this->addField($f);
//        return $f;
//    }

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
