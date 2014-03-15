<?php
namespace Toys\Web;

class Renderer
{

    public function __construct()
    {
    }

    public function render()
    {
        $context = Application::singleton()->getContext();
        $result = $context->result;
        $response = $context->response;
        switch ($result->getType()) {
            case 'template' :
                $tmpl = new Template($result->data);
                $response->write($tmpl->render($result->path));
                break;
            case 'content' :
                $response->write($result->content);
                break;
            case 'redirect' :
                $response->redirect($result->url);
                break;
//            case 'referer' :
//                $to = array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER'] : $result->getUrl();
//                $response->redirect($to);
//                break;
//            case 'callback' :
//                call_user_func_array($result->getCallback(), $result->getArguments());
//                break;
            case 'json' :
//                $data = $result->getData();
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
