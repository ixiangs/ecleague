<?php
namespace Ecleague;

use Toy\Loader;
use Toy\Web\Application;

class Handler {

	public function handle() {
		$context = Application::$context;
        $router = $context->router;

        $ctrlClass = str_replace(' ', '', ucwords(str_replace('-', ' ', $router -> component)));
		$ctrlClass .= '\\'.ucfirst($router->domain->getNamespace());
		$ctrlClass .= '\\'.str_replace(' ', '', ucwords(str_replace('-', ' ', $router -> controller))).'Controller';

		$inst = Loader::create($ctrlClass);
		$inst -> initialize($context);
		return $inst -> execute($router->action);
	}

}
