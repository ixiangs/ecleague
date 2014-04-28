<?php
namespace Toy\View\Html;

use Toy\Web\Application;

class Pagination extends Element
{
    private $_rowCount = null;
    private $_pageSize = null;
    private $_pageRange = null;

    public function __construct($rowCount, $pageSize, $pageRange){
        parent::__construct('ul', array('class'=>'pagination'));
        $this->_rowCount = $rowCount;
        $this->_pageSize = $pageSize;
        $this->_pageRange = $pageRange;
    }

    public function render(){
        $router = Application::$context->router;
        $request = Application::$context->request;
        $pageIndex = $request->getParameter('pageindex', 1);
        $pc = ceil($this->_rowCount / $this->_pageSize);
        $start = 0;
        $end = 0;
        if($pc < $this->_pageRange){
            $start = 1;
            $end = $pc;
        } else if($pageIndex == $pc){
            $start = $pc - $this->_pageRange - 1;
            if($start <= 1){
                $start = 1;
            }
            $end = $pc;
        } else {
            $cpr = floor($pageIndex / $this->_pageRange);
            $start = $cpr <= 0? 1: $cpr*$this->_pageRange - 1;
            if($start <= 1){
                $start = 1;
            }

            $end = $start + $this->_pageRange + 1;
            if($end > $pc) {
                $start = $pc - $this->_pageRange - 1;
                if($start < 1){
                    $start = 1;
                }
                $end = $pc;
            }
        }

        $pargs = $request->getParameter();
        $pargs['pageindex'] = 1;
        $html = array(
            $this->renderBegin()
        );
        if($pageIndex > $this->_pageRange) {
            $html[] = sprintf("<li><a href=\"%s\">&larr; %s</a></li>", $router->buildUrl().'?'.http_build_query($pargs) , 1);
        }

        for($i = $start; $i <= $end; $i++){
            if($i == $pageIndex) {
                $html[] = sprintf("<li class=\"active\"><a href=\"#\">%s</a></li>", $i);
            } else {
                $pargs['pageindex'] = $i;
                $html[] = sprintf("<li><a href=\"%s\">%s</a></li>", $router->buildUrl().'?'.http_build_query($pargs), $i);
            }
        }

        if($pageIndex < $pc-$this->_pageRange) {
            $pargs['pageindex'] = $pc;
            $html[] = sprintf("<li><a href=\"%s\">%s &rarr;</a></li>", $router->buildUrl().'?'.http_build_query($pargs), $pc);
        }
        $html[] = $this->renderEnd();
        return implode('', $html);
    }

}