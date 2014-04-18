<?php
namespace Toy\View\Html;

class TreeSelect extends Select
{

    private $_sortedOptions = array();

    public function __construct($attrs = array())
    {
        parent::__construct($attrs);
    }

    public function renderInner()
    {
        $html = array();
        if (!is_null($this->caption)) {
            $html[] = '<option value="">' . $this->caption . '</option>';
        }

        $this->sortOptions();
        foreach ($this->_sortedOptions as $option) {
            $text = str_repeat('- ', $option['level']).$option['text'];
            if ($this->value == $option['value']) {
                $html[] = '<option value="'.$option['value'].'" selected>'.$text.'</option>';
            } else {
                $html[] = '<option value="'.$option['value'].'">'.$text.'</option>';
            }
        }
        return implode('', $html);
    }

    private function sortOptions($parentId = 0, $level = 0)
    {
        foreach($this->options as $option){
            if($option['parentId'] == $parentId){
                $option['level'] = $level;
                $this->_sortedOptions[] = $option;
                $this->sortOptions($option['id'], ++$level);
                --$level;
            }
        }
    }
}