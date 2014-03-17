<?php
namespace Toy\Web;

class Handler {

	public function handle() {
		$context = Application::singleton() -> getContext();
        $router = $context->router;

//        $ctrlClass = Configuration::$codeNamespaces[0];
        $ctrlClass = 'Components\\'.str_replace(' ', '', ucwords(str_replace('-', ' ', $router -> component)));
		$ctrlClass .= '\\'.ucfirst($router->domain->getNamespace());
		$ctrlClass .= '\\'.str_replace(' ', '', ucwords(str_replace('-', ' ', $router -> controller))).'Controller';

		$inst = new $ctrlClass();
		$inst -> initialize($context);
		return $inst -> execute($router->action);
	}

}
