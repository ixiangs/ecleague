<?php
namespace Toy\Web;

class Handler
{

    public function handle()
    {
        $router = Application::$context->router;
        $controllerClass = "Codes\\".
            str_replace(' ', '', ucwords(str_replace('-', ' ', $router->component)))."\\".
            $router->domain->getNamespace()."\\".
            str_replace(' ', '', ucwords(str_replace('-', ' ', $router->controller))).'Controller';
        $inst = new $controllerClass();
        $inst->initialize(Application::$context);
        $inst->ready();
        $result = $inst->execute($router->action);
        $inst->finish();
        return $result;
    }

}
