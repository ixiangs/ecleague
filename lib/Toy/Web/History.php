<?php
namespace Toy\Web;


final class History
{

    private $_records = array();
    private $_session = null;

    public function __construct()
    {
        $this->_session = Application::$context->session;
        $this->_records = $this->_session->get('__histories');
    }

    public function getRecords()
    {
        return $this->_records;
    }

    public function append($action, $arguments)
    {
        array_unshift($this->_records, array('action' => $action, 'arguments' => $arguments));
        if (count($this->_records) >= 10) {
            unset($this->_records[10]);
        }
        $this->_session->set('__histories', $this->_records);
        return $this;
    }

    public function find($action)
    {
        foreach ($this->_records as $record) {
            if ($record['action'] == $action) {
                return $record;
            }
        }
        return null;
    }
} 