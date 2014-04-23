<?php
namespace Toy\View\Html;

class Form extends Element
{

    protected $fields = array();
    protected $hiddens = array();

    public function __construct($id = 'form1', $method = 'post')
    {
        parent::__construct('form', array('class' => 'form-horizontal', 'id' => $id, 'method' => $method));
    }

    public function getHiddens()
    {
        return $this->hiddens;
    }

    public function addField($field)
    {
        $this->fields[] = $field;
        return $this;
    }

    public function addLabelField($label, $value = null)
    {
        $f = new LabelField($label);
        $f->getInput()->setAttribute(array('text' => $value));
        $this->addField($f);
    }

    public function addInputField($type, $label, $id, $name, $value = null)
    {
        $f = new InputField($type, $label);
        $f->getInput()->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value));
        $this->addField($f);
        if ($type == 'file') {
            $this->setAttribute('enctype', 'multipart/form-data');
        }
        return $f;
    }

    public function addTextareaField($label, $id, $name, $value = null)
    {
        $f = new TextareaField($label);
        $f->getInput()->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value));
        $this->addField($f);
        return $f;
    }

    public function addSelectField($options, $label, $id, $name, $value = null)
    {
        $f = new SelectField($label);
        $f->getInput()
            ->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value))
            ->setOptions($options);
        $this->addField($f);
        return $f;
    }

    public function addTreeSelectField($options, $label, $id, $name, $value = null)
    {
        $f = new TreeSelectField($label);
        $f->getInput()
            ->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value))
            ->setOptions($options);
        $this->addField($f);
        return $f;
    }

    public function addCheckboxListField($options, $label, $id, $name, $value = null)
    {
        $f = new OptionListField($label, true);
        $f->getInput()
            ->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value))
            ->setOptions($options);
        $this->addField($f);
        return $f;
    }

    public function addRadioButtonListField($options, $label, $id, $name, $value = null)
    {
        $f = new OptionListField($label, false);
        $f->getInput()
            ->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value))
            ->setOptions($options);
        $this->addField($f);
        return $f;
    }

    public function addInputGroupField($type, $label, $id, $name, $value = null)
    {
        $f = new InputGroupField($type, $label);
        $f->getInput()->setAttribute(array('id' => $id, 'name' => $name, 'value' => $value));
        $this->addField($f);
        return $f;
    }

    public function addHiddenField($id, $name, $value = null)
    {
        $f = new Element('input', array('type' => 'hidden', 'id' => $id, 'name' => $name, 'value' => $value));
        $this->hiddens[] = $f;
        return $f;
    }

    public function renderBegin()
    {
        foreach ($this->fields as $f) {
            if (count($f->getValidateRules()) > 0) {
                $this->setAttribute('data-validate', 'true');
                break;
            }
        }
        return parent::renderBegin();
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
