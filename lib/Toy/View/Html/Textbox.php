<?php
namespace Toy\View\Html;

class Textbox extends VerifiableElement
{

    public function __construct($type)
    {
        parent::__construct('input', array(
            'type' => $type,
            'class' => 'form-control'
        ));
    }
}