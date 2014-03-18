<?php
namespace Core\Html\Widget;

use Core\Html\Element;
use Toy\Util\StringUtil;

class DataTable extends Element{

    private $_columns = array();
    private $_dataSource = null;

    public function __construct($dataSource = null, $id = null){
        parent::__construct('table');
        $this->setId($id)->setCss('table table-striped table-bordered table-hover')->setDataSource($dataSource);
    }

    public function setDataSource($value){
        $this->_dataSource = $value;
        return $this;
    }

    public function getColumns(){
        return $this->_columns;
    }

    public function addIndexColumn($headText, $headCss = null, $cellCss = null){
        $col = new IndexColumn();
        $col->setHeadText($headText)->setHeadCss($headCss)->setCellCss($cellCss);
        $this->_columns[] = $col;
        return $col;
    }

    public function addLabelColumn($headText, $cellText, $headCss = null, $cellCss = null){
        $col = new LabelColumn();
        $col->setHeadText($headText)->setCellText($cellText)->setHeadCss($headCss)->setCellCss($cellCss);
        $this->_columns[] = $col;
        return $col;
    }

    public function addOptionColumn($headText, $cellText, $options, $headCss = null, $cellCss = null){
        $col = new OptionColumn();
        $col->setOptions($options)->setHeadText($headText)->setCellText($cellText)->setHeadCss($headCss)->setCellCss($cellCss);
        $this->_columns[] = $col;
        return $col;
    }

    public function addLinkColumn($headText, $cellText, $link, $headCss = null, $cellCss = null){
        $col = new LinkColumn();
        $col->setLink($link)->setHeadText($headText)->setCellText($cellText)->setHeadCss($headCss)->setCellCss($cellCss);
        $this->_columns[] = $col;
        return $col;
    }

    public function render(){
        $head = array();
        $body = array();
        $footer = array();
        foreach($this->_columns as $col){
            $head[] = $col->getHeadHtml();
        }
        foreach($this->_dataSource as $index=>$dataRow){
            $cells = array();
            foreach($this->_columns as $col){
                $cells[] = $col->getCellHtml($dataRow, $index);
            }
            $body[] = $cells;
        }

        $result = array($this->getStartHtml());
        $result[] = '<thead><tr>';
        $result[] = implode('', $head);
        $result[] = '</tr></thead><tbody>';
        foreach($body as $row){
            $result[] = '<tr>'.implode('', $row).'</tr>';
        }
        $result[] = '</tbody></table>';
        return implode('', $result);
    }

//    protected function getCellHtml($column, $row){
//        $res = $column->getCell()->getStartHtml();
//        switch($column->getType()){
//            case 'label':
//                $res .= StringUtil::substitute($column->getCellText(), $row);
//                break;
//        }
//        $res .= $column->getCell()->getEndHtml();
//        return $res;
//    }

//    protected function renderFooter($column){
//
//    }
}