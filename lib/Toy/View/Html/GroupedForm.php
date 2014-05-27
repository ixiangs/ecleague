<?php
namespace Toy\View\Html;

class GroupedForm extends Form
{

    protected $groups = array();
    private $_currentGroup = null;

    public function beginGroup($id, $title)
    {
        $this->_currentGroup = array('id' => $id, 'title' => $title, 'fields' => array());
        return $this;
    }

    public function nextGroup($id, $title)
    {
        $this->groups[] = $this->_currentGroup;
        $this->_currentGroup = array('id' => $id, 'title' => $title, 'fields' => array());
        return $this;
    }

    public function endGroup()
    {
        $this->groups[] = $this->_currentGroup;
        return $this;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function addField($field)
    {
        $this->_currentGroup['fields'][] = $field;
        $this->fields[] = $field;
        return $this;
    }

    public function renderInner()
    {
        $res = array();
        foreach ($this->groups as $group) {
            $res[] = '<div id="' . $group['id'] . '" class="panel panel-default">';
            $res[] = '<div class="panel-heading">' . $group['title'] . '</div>';
            $res[] = '<div class="panel-body">';
            foreach ($group['fields'] as $f) {
                $res[] = $f->render();
            }
            $res[] = '</div></div>';
        }
        foreach ($this->hiddens as $f) {
            $res[] = $f->render();
        }
        return implode('', $res);
    }
}
