<?php
namespace Core\Html\Widget;

class TableForm extends Table
{
    protected $hiddens = array();
    protected $form = null;

    public function __construct($dataSource = null, $id = 'table1')
    {
        parent::__construct($dataSource, $id);
        $this->form = new Element('form', array('id'=>'table_form', 'method'=>'post'));
    }

    public function getForm(){
        return $this->form;
    }

    public function addHidden($id, $name, $value = null)
    {
        $f = new Element('input', array('type' => 'hidden', 'id' => $id, 'name' => $name, 'value' => $value));
        $this->hiddens[] = $f;
        return $this;
    }

    public function render(){
        $res = $this->form->renderBegin();
        $res .= parent::render();
        foreach ($this->hiddens as $f) {
            $res .= $f->render();
        }
        $res .= $this->form->renderEnd();
        return $res;
    }
}