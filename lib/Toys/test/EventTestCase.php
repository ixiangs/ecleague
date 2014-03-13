<?php
use Toys\Unit\TestCase;
use Toys\Event\Configuration;
use Toys\Event\Dispatcher;

class EventTestCase extends TestCase {
	
	public function __construct(){
		Configuration::addEvent('E1', 'E2');
	}
	
	public function testDispatcher(){
		$one = false;
		$two = false;
		$three = false;
		Configuration::addListener('E1', function($source, $argument)use(&$one){
			$one = true;
			$argument->setCancelled(true);
		});
		Configuration::addListener('E1', function($source, $argument)use(&$two){
			$two = true;
		});		
		Configuration::addListener('E2', function($source, $argument)use(&$three){
			$three = true;
		});		
		Dispatcher::dispatch('E1', $this);
		Dispatcher::dispatch('E2', $this);
		
		$this->assertEqual(true, $one);
		$this->assertEqual(false, $two);
		$this->assertEqual(true, $three);
	}
}
