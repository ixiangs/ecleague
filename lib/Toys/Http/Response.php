<?php
namespace Toys\Http;

class Response
{

    public function __construct()
    {
    }

    private $_contents = array();

    public function getContent()
    {
        return implode('', $this->_contents);
    }

    public function append($content)
    {
        $this->_contents[] = $content;
        return $this;
    }

    public function write($content)
    {
        return $this->clear()->append($content);
    }

    public function clear()
    {
        $this->_contents = array();
        return $this;
    }

    public function flush()
    {
        // if(Soul_Config::get('output.compressible') && !Soul_Config::get('debug')){
        // 	if (Soul_Registry::server()->extensionIsLoaded('zlib') &&
        // 	     array_key_exists("HTTP_ACCEPT_ENCODING", $_SERVER) &&
        // 	     strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")){
        // 		ob_start("ob_gzhandler");
        // 	}

        // 	echo $this->getContent();

        // 	if(Soul_Registry::server()->extensionIsLoaded('zlib') &&
        // 	     array_key_exists("HTTP_ACCEPT_ENCODING", $_SERVER) &&
        // 	     strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")) {
        // 		ob_end_flush();
        //           }
        // }else{
        echo $this->getContent();
        // }
    }

    public function setHeader($header, $replaced = TRUE, $http_response_code = 200)
    {
        header($header, $replaced, $http_response_code);
        return $this;
    }

    // public function setStatus($status){
    // header("HTTP/1.0 ".$status);
    // return $this;
    // }

    public function download($filename, $content)
    {
        $this->setDownloadHeader($filename, strlen($content));
        $this->write($content);
        return $this;
    }

    public function setDownloadHeader($filename, $size)
    {
        header('Content-Description: File Transfer');
        header("Content-Type: application/octet-stream");
        header('Content-Transfer-Encoding: binary');
        header("Accept-Ranges:bytes");
        header('Content-Disposition: attachment; filename=' . $filename);
        header("Accept-Length:" . $size);
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }

    // public function end(){
    // 	Soul_Framework_Application::singleton()->quit();
    // }

    public function redirect($url)
    {
        header("location:$url");
        $this->end();
    }
}
