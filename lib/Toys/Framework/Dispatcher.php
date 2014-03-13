<?php
namespace Toys\Framework;

use Toys\Util\ArrayUtil, Toys\Util\StringUtil, Toys\Util\PathUtil;

class Dispatcher {

	public function dispatch() {
		$context = Application::singleton() -> getContext();
		$objective = $context -> getObjective();
		$domain = $context -> getDomain();
		$ctrlClass = str_replace(' ', '\\', ucwords(str_replace('_', ' ', $objective -> getComponent())));
		$ctrlClass .= '\\'.$domain->getNamespace();
		$ctrlClass .= '\\'.StringUtil::SplitToPascalCasing('-', $objective -> getController()).'Controller';
		$inst = new $ctrlClass();
		$inst -> initialize($context);
		return $inst -> execute($objective->getAction());
	}
	
	// public function call($domain, $component, $controller, $action, $parameters = NULL){
		// $context = Application::singleton() -> getContext();
		// // include_once(PathUtil::combines(Configuration::$componentDirectory, $component, $domain, $controller.'Controller', '.php'));
		// $className = '\\' . $component.'\\'.$domain.'\\'.$controller.'Controller';
		// $controller = new $className();
		// $controller -> initialize($context);
		// return $controller -> execute($action, $parameters);		
	// }

}
