<?php
namespace Toy\Web;

use Toy\View;
use Toy\Html\Document;

class Renderer
{

    public function render()
    {
        $context = Application::$context;
        $result = $context->result;
        $response = $context->response;
        $router = $context->router;
        switch ($result->getType()) {
            case 'template' :
                $action = $router->action;
                $component = $router->component;
                $controller = $router->controller;
                $path = $result->path;
                $parts = array($component, $controller, $action);
                if (empty($path)) {
                    $path = str_replace('_', '/', implode('/', $parts));
                } elseif ($path[0] != '/') {
                    $arr = explode('/', $path);
                    $cnt = count($arr);
                    if ($cnt == 3) {
                        $parts = $arr;
                    }
                    if ($cnt == 2) {
                        $parts[1] = $arr[0];
                        $parts[2] = $arr[1];
                    }
                    if ($cnt == 1) {
                        $parts[2] = $arr[0];
                    }
                    $path = str_replace('_', '/', implode('/', $parts));
                }

                $temp = new Template($result->data);
                $response->write($temp->render($path));
                break;
            case 'content' :
                $response->write($result->content);
                break;
            case 'redirect' :
                if ($result->message) {
                    $context->session->set('infos', $result->message);
                }
                $response->redirect($result->url);
                break;
            case 'json' :
                $response
                    ->setHeader('content-type:application/json; charst=utf-8')
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
