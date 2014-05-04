<?php
namespace Toy\View\Html;

class Textarea extends VerifiableElement
{

    public function __construct()
    {
        parent::__construct('textarea', array(
            'class' => 'form-control'
        ));
    }

    public function render()
    {
        $text = $this->getAttribute('value');
        $this->removeAttribute('value');
        $this->setAttribute('text', $text);
    }
}