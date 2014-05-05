<?php
namespace Toy\Web;

use Toy\Loader;
use Toy\Web\Interfaces\IHandler;

class Handler{

	public function handle() {
		$context = Application::singleton() -> getContext();
        $router = $context->router;

//        $ctrlClass = Configuration::$codeNamespaces[0];
        $ctrlClass = str_replace(' ', '', ucwords(str_replace('-', ' ', $router -> component)));
		$ctrlClass .= '\\'.ucfirst($router->domain->getNamespace());
		$ctrlClass .= '\\'.str_replace(' ', '', ucwords(str_replace('-', ' ', $router -> controller))).'Controller';

		$inst = Loader::create($ctrlClass);
		$inst -> initialize($context);
		return $inst -> execute($router->action);
	}

}
