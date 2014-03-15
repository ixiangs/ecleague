<?php
namespace Toys\Http;

class Session
{

    private $_abort = false;

    public function exists($key)
    {
        return isset($_SESSION[$key]);
    }

    public function get($key, $default = NULL)
    {
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
    }

    public function setIfEmpty($key, $value)
    {
        if (!$this->exists($key)) {
            $_SESSION[$key] = $value;
        }
        return $this;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    public function remove($key)
    {
        if (array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
        return $this;
    }

    public function pop($key)
    {
        $result = $this->get($key);
        $this->remove($key);
        return $result;
    }

    public function appendToArray($key, $value)
    {
        $arr = $this->get($key, array());
        $arr[] = $value;
        $this->set($key, $arr);
        return $this;
    }

    public function clear()
    {
        session_unset();
        return $this;
    }

    public function regenerateId()
    {
        return session_regenerate_id();
    }

    public function getId()
    {
        return session_id();
    }

    public function start()
    {
        if (isset($_GET[session_name()])) {
            session_id($_GET[session_name()]);
        }

        session_start();
        if (!isset($_SESSION['_safesession'])) {
            session_regenerate_id(TRUE);
            $_SESSION['_safesession'] = '1';
        }
        $_SESSION['_lastactivetime'] = time();
        return $this;
    }

    public function abort()
    {
        $this->_abort = TRUE;
        setcookie(session_name(), '', time() - 3600, '/');
        session_unset();
        session_destroy();
        return $this;
    }

}
