<?php
namespace Core\Html\Widget;

class InputField extends BaseField
{
    private $_input = null;

    public function __construct($type, $label)
    {
        parent::__construct($label);
        $this->_input = new Element('input', array(
           'type'=>$type,
           'class'=>'form-control'
        ));
    }

    public function getInput(){
        return $this->_input;
    }

    protected function renderInput()
    {
        foreach ($this->getValidateRules() as $n => $v) {
            if(is_array($v)){
                $this->_input->setAttribute('data-validate-' . $n, $v['value'] === true? 'true': $v['value']);
                $this->_input->setAttribute('data-validate-'.$n.'-msg', $v['message']);
            }else{
                $this->_input->setAttribute('data-validate-' . $n, $v === true? 'true': $v);
            }
        }

        return $this->_input->render();
    }
}