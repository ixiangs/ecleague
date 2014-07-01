<?php
namespace Toy\Log;

class ConsoleAppender extends BaseAppender
{

//	private $_settings = array();
//
//	public function __construct($settings){
//		$this->_settings = $settings;
//	}

    public function append($content)
    {
        print $content;
    }
}
