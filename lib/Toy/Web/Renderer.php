<?php
namespace Toy\Web;

use Toy\View;
use Toy\View\Html\Document;

class Renderer
{

    public function render()
    {
        $context = Application::$context;
        $result = $context->result;
        $request = $context->request;
        $response = $context->response;
        $router = $context->router;
        switch ($result->getType()) {
            case 'template' :
                $lang = $request->getBrowserLanguage();
                $action = $router->action;
                $component = $router->component;
                $controller = $router->controller;
                $domain = strtolower($router->domain->getName());
                View\Configuration::$templateDirectories = array($domain);
                Document::singleton()->setBreadcrumbs(Application::getRequestComponent()->getActionBreadcrumb());

                $tmpl = new View\Template(array_merge(array(
                    'router'=>$context->router,
                    'request'=>$context->request,
                    'session'=>$context->session,
                    'applicationContext'=>$context
                ), $result->data));

                $path = $result->path? $result->path: str_replace('_', '/', $component.'/'.$controller . '/' . $action);
                $paths = array(
                    $lang . '/' . $path,
                    $path
                );
                $response->write($tmpl->render($paths));
                break;
            case 'content' :
                $response->write($result->content);
                break;
            case 'redirect' :
                if($result->message){
                    $context->session->set('infos', $result->message);
                }
                $response->redirect($result->url);
                break;
            case 'json' :
                $response->setHeader('content-type:application/json; charst=utf-8')
                    ->write(json_encode($result->data));
                break;
            case 'download':
                $content = $result->content;
                header('Content-Description: File Transfer');
                header("Content-Type: application/octet-stream");
                header('Content-Transfer-Encoding: binary');
                header("Accept-Ranges:bytes");
                header('Content-Disposition: attachment; filename=' . $result->filename);
                header("Accept-Length:" . strlen($content));
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                $response->write($content);
                break;
            case 'end' :
                Application::singleton()->end();
                break;
        }
    }
}
