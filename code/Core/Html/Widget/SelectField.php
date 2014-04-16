<?php
namespace Core\Html\Widget;

class SelectField extends BaseField
{

//    private $_select = null;

    public function __construct($label)
    {
        parent::__construct($label);
        $this->input = new Select(array('class'=>'form-control'));
    }

//    public function getSelect(){
//        return $this->_select;
//    }

    protected function renderInput()
    {
        foreach ($this->getValidateRules() as $n => $v) {
            if(is_array($v)){
                $this->input->setAttribute('data-validate-' . $n, $v['value'] === true? 'true': $v['value']);
                $this->input->setAttribute('data-validate-'.$n.'-msg', $v['message']);
            }else{
                $this->input->setAttribute('data-validate-' . $n, $v === true? 'true': $v);
            }
        }

        return $this->input->render();
    }
}