<?php
namespace Toy\View\Html;

class CustomField extends FormField
{
    public function __construct()
    {
        parent::__construct(null);
    }

    protected function renderInput()
    {
        return '';
    }
}