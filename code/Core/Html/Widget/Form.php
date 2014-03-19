<?php
namespace Core\Html\Widget;

class Form extends Element{

    private $_fields = array();
    private $_buttons = array();
    private $_hiddens = array();

    public function __construct($id='form1', $method='post'){
        parent::__construct('form');
        $this->setCss('form-horizontal')->setId($id)->addAttribute('method', $method);
    }

    public function addField($field){
        $this->_fields[] = $field;
        return $this;
    }
	
	public function addInputField($type, $label, $id, $name, $value){
        $f = new InputField($type, $label, $id, $name, $value);
        $this->addField($f);
        return $f;
	}

    public function addSelectField($options, $label, $id, $name, $value){
        $f = new SelectField($label, $id, $name, $value);
        $f->setOptions($options);
        $this->addField($f);
        return $f;
    }

    public function addCheckboxesField($options, $label, $id, $name, $value){
        $f = new CheckboxesField($label, $id, $name, $value);
        $f->setOptions($options);
        $this->addField($f);
        return $f;
    }

    public function addHiddenField($id, $name, $value){
        $f = new Element('input');
        $f->addAttribute('type', 'hidden')->setId($id)->setName($name)->addAttribute('value', $value);
        $this->_hiddens[] = $f;
        return $f;
    }

    public function addButton($type, $text, $css = 'btn', $attrs = array()){
        $b = new Element('button');
        $b->setCss($css)->setAttributes($attrs)->addAttribute('type', $type)->addAttribute('text', $text);
        $this->_buttons[] = $b;
        return $b;
    }

    public function renderBegin(){
        foreach($this->_fields as $f){
            if(count($f->getValidateRules()) > 0){
                $this->addAttribute('data-validate', 'true');
                break;
            }
        }
        return parent::renderBegin();
    }

    public function renderInner(){
        $res = array();
        foreach($this->_fields as $f){
            $res[] = $f->render();
        }
        $res[] = '<div class="form-group"><div class="col-lg-10 col-lg-offset-2">';
        foreach($this->_buttons as $b){
            $txt = $b->getAttribute('text');
            $b->removeAttribute('text');
            $res[] = $b->renderBegin().$txt.$b->renderEnd();
        }
        $res[] = '</div></div>';
        foreach($this->_hiddens as $f){
            $res[] = $f->render();
        }
        return implode('', $res);
    }
}
