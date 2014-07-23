<?php
namespace Toy\Http;

use Toy\Util\StringUtil;

class Request implements \ArrayAccess
{

    public function __construct()
    {
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
        return $this;
    }

    public function offsetExists($var)
    {
        return $this->exists($var);
    }

    public function offsetUnset($var)
    {
        unset($_REQUEST[$var]);
        return $this;
    }

    public function offsetGet($var)
    {
        return $this->get($var);
    }

    public function isAjax()
    {
        return (array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            || (array_key_exists('X-Requested-With', $_SERVER) && strtolower($_SERVER['X-Requested-With']) == 'xmlhttprequest');
    }

    public function isHttps()
    {
        return isset($_SERVER['HTTPS']);
    }

    public function getServerPort()
    {
        return $_SERVER['SERVER_PORT'];
    }

    public function getServerName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    public function getRemoteAddress()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    public function getUri()
    {
        return $_SERVER["REQUEST_URI"];
    }

    public function getMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function isWindows()
    {
        return strpos($_SERVER['HTTP_USER_AGENT'], 'Windows');
    }

    public function getBrowserLanguage()
    {
        $others = array(
            'zh-Hans-CN' => 'zh-CN',
            'zh-Hans' => 'zh-CN'
        );
        $arr = explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        if (array_key_exists($arr[0], $others)) {
            return strtolower($others[$arr[0]]);
        }
        return strtolower($arr[0]);
    }

    public function getUserAgent()
    {
        if (strpos($_SERVER["HTTP_USER_AGENT"], "MSIE 8.0")) {
            return "IE8";
        } elseif (strpos($_SERVER["HTTP_USER_AGENT"], "MSIE 7.0")) {
            return "IE7";
        } elseif (strpos($_SERVER["HTTP_USER_AGENT"], "MSIE 6.0")) {
            return "IE6";
        } elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Firefox/7")) {
            return "FF7";
        } elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Firefox/6")) {
            return "FF6";
        } elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Firefox/5")) {
            return "FF5";
        } elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Firefox/4")) {
            return "FF4";
        } elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Firefox/3")) {
            return "FF3";
        } elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Firefox/2")) {
            return "FF2";
        } elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Chrome")) {
            return "Chrome";
        } elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Safari")) {
            return "Safari";
        } elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Opera")) {
            return "Opera";
        } else {
            return $_SERVER["HTTP_USER_AGENT"];
        }
    }

    public function getVirtualPath()
    {
        return StringUtil::subString($_SERVER['REQUEST_URI'], '?');
    }

    public function getHostName()
    {
        $result = $this->getServerName();
        $port = $this->getServerPort();
        if ($port != '80') {
            return $result . ':' . $port;
        }
        return $result;
    }

    public function getRefererUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    public function isGetMethod()
    {
        return strtolower($this->getMethod()) == 'get';
    }

    public function isPostMethod()
    {
        return strtolower($this->getMethod()) == 'post';
    }

    public function isDeleteMethod()
    {
        return strtolower($this->getMethod()) == 'delete';
    }

    public function isPutMethod()
    {
        return strtolower($this->getMethod()) == 'put';
    }

    public function isOptionsMethod()
    {
        return strtolower($this->getMethod()) == 'options';
    }

    public function isHeadMethod()
    {
        return strtolower($this->getMethod()) == 'head';
    }

    public function hasParameter($name)
    {
        return array_key_exists($name, $_REQUEST);
    }

    public function getParameter($name, $default = null)
    {
        return array_key_exists($name, $_REQUEST) ? $_REQUEST[$name] : $default;
    }

    public function listParameter()
    {
        $args = func_get_args();
        $result = array();
        foreach ($args as $arg) {
            $result[] = array_key_exists($arg, $_REQUEST) ? $_REQUEST[$arg] : null;
        }
        return $result;
    }

    public function getAllParameters()
    {
        return $_REQUEST;
    }

    public function getQuery($name, $default = null)
    {
        return array_key_exists($name, $_GET) ? $_GET[$name] : $default;
    }

    public function listQuery()
    {
        $args = func_get_args();
        $result = array();
        foreach ($args as $arg) {
            $result[] = array_key_exists($arg, $_GET) ? $_GET[$arg] : null;
        }
        return $result;
    }

    public function getAllQuery()
    {
        return $_GET;
    }

    public function getPost($name, $default = null)
    {
        return array_key_exists($name, $_POST) ? $_POST[$name] : $default;
    }

    public function listPost()
    {
        $args = func_get_args();
        $result = array();
        foreach ($args as $arg) {
            $result[] = array_key_exists($arg, $_POST) ? $_POST[$arg] : null;
        }
        return $result;
    }

    public function getAllPost()
    {
        return $_POST;
    }

    public function getFile($name)
    {
        if (array_key_exists($name, $_FILES) && $_FILES[$name]['error'] != UPLOAD_ERR_NO_FILE) {
            return new File($_FILES[$name]);
        }
        return NULL;
    }

}
