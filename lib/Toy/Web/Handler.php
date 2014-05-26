<?php
namespace Toy\Web;

class Handler
{

    public function handle()
    {
        $router = Application::$context->router;
        $controllerClass = $router->component.'_'.str_replace(' ', '', ucwords(str_replace('-', ' ', $router->controller)));
        $controllerClass = '\\' . str_replace(' ', '\\', ucwords(str_replace('_', ' ', $controllerClass))) . 'Controller';
        $controllerPath = Configuration::$controllerDirectory.$router->domain->getName() . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $controllerClass) . '.php';
        include_once $controllerPath;
        $inst = new $controllerClass();
        $inst->initialize(Application::$context);
        $inst->ready();
        $result = $inst->execute($router->action);
        $inst->finish();
        return $result;
    }

}
