<?php
namespace Core\Html\Widget;

class Form extends Element
{

    private $_fields = array();
    private $_buttons = array();
    private $_hiddens = array();

    public function __construct($id = 'form1', $method = 'post')
    {
        parent::__construct('form', array('class'=>'form-horizontal', 'id'=>$id, 'method'=>$method));
    }

    public function addField($field)
    {
        $this->_fields[] = $field;
        return $this;
    }

    public function addLabelField($label, $value)
    {
        $f = new LabelField($label);
        $f->getInput()->setAttribute(array('text'=>$value));
        $this->addField($f);
    }

        public function addInputField($type, $label, $id, $name, $value)
    {
        $f = new InputField($type, $label);
        $f->getInput()->setAttribute(array('id'=>$id, 'name'=>$name, 'value'=>$value));
        $this->addField($f);
        if($type == 'file'){
            $this->setAttribute('enctype', 'multipart/form-data');
        }
        return $f;
    }

    public function addSelectField($options, $label, $id, $name, $value)
    {
        $f = new SelectField($label);
        $f->getSelect()
            ->setAttribute(array('id'=>$id, 'name'=>$name,'value'=>$value))
            ->setOptions($options);
        $this->addField($f);
        return $f;
    }

    public function addCheckboxListField($options, $label, $id, $name, $value)
    {
        $f = new CheckboxListField($label);
        $f->getCheckboxes()
            ->setAttribute(array('id'=>$id, 'name'=>$name,'value'=>$value))
            ->setOptions($options);
        $this->addField($f);
        return $f;
    }

    public function addHiddenField($id, $name, $value)
    {
        $f = new Element('input', array('type'=>'hidden', 'id'=>$id, 'name'=>$name, 'value'=>$value));
        $this->_hiddens[] = $f;
        return $f;
    }

    public function addButton($type, $text, $css = 'btn')
    {
        $b = new Element('button', array('type'=>$type, 'text'=>$text, 'class'=>$css));
        $this->_buttons[] = $b;
        return $b;
    }

    public function renderBegin()
    {
        foreach ($this->_fields as $f) {
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
        foreach ($this->_fields as $f) {
            $res[] = $f->render();
        }
        if (count($this->_buttons) > 0) {
            $res[] = '<div class="form-group"><div class="col-lg-10 col-lg-offset-2">';
            foreach ($this->_buttons as $b) {
                $txt = $b->getAttribute('text');
                $b->removeAttribute('text');
                $res[] = $b->renderBegin() . $txt . $b->renderEnd();
            }
            $res[] = '</div></div>';
        }
        foreach ($this->_hiddens as $f) {
            $res[] = $f->render();
        }
        return implode('', $res);
    }
}
