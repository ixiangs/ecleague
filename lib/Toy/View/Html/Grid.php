<?php
namespace Toy\View\Html;

class Grid extends Element
{

    private $_columns = array();
    private $_dataSource = null;
    private $_rowSelectable = false;
    protected $headVisible = true;
    protected $footVisible = false;

    public function __construct($dataSource = null, $id = 'table1')
    {
        parent::__construct('table', array('id' => $id, 'class' => 'table table-striped table-bordered table-hover'));
        $this->setDataSource($dataSource);
    }

    public function getRowSelectable()
    {
        return $this->_rowSelectable;
    }

    public function setDataSource($value)
    {
        $this->_dataSource = $value;
        return $this;
    }

    public function getColumns()
    {
        return $this->_columns;
    }

    public function addColumn($col)
    {
        $this->_columns[] = $col;
        return $this;
    }

    public function addIndexColumn($headText, $headCss = null, $cellCss = null)
    {
        $col = new IndexColumn();
        $col->getHead()->setAttribute(array('class' => $headCss, 'text' => $headText));
        $col->getCell()->setAttribute('class', $cellCss);
        $this->addColumn($col);
        return $col;
    }

    public function addLabelColumn($headText, $cellText, $headCss = null, $cellCss = null)
    {
        $col = new LabelColumn();
        $col->getHead()->setAttribute(array('class' => $headCss, 'text' => $headText));
        $col->getCell()->setAttribute('class', $cellCss)
            ->getChild(0)->setAttribute(array('text' => $cellText));
        $this->addColumn($col);
        return $col;
    }

    public function addOptionColumn($headText, $cellText, $options, $headCss = null, $cellCss = null)
    {
        $col = new OptionColumn();
        $col->setOptions($options);
        $col->getHead()->setAttribute(array('class' => $headCss, 'text' => $headText));
        $col->getCell()->setAttribute('class', $cellCss)
            ->getChild(0)->setAttribute('text', $cellText);
        $this->addColumn($col);
        return $col;
    }

    public function addLinkColumn($headText, $cellText, $link, $headCss = null, $cellCss = null)
    {
        $col = new LinkColumn();
        $col->getHead()->setAttribute(array('class' => $headCss, 'text' => $headText));
        $col->getCell()->setAttribute('class', $cellCss)
            ->getChild(0)->setAttribute(array('text' => $cellText, 'href' => $link));
        $this->addColumn($col);
        return $col;
    }

    public function addCheckboxColumn($checkboxName, $checkboxValue, $checkboxId, $headCss = null, $cellCss = null)
    {
        $col = new CheckboxColumn();
        $col->getHead()->setAttribute('class', $headCss);
        $col->getCell()->setAttribute('class', $cellCss)
            ->getChild(0)->setAttribute(array(
            'name' => $checkboxName,
            'value' => $checkboxValue,
            'id' => $checkboxId));
        $this->addColumn($col);
        return $col;
    }

    public function addSelectableColumn($checkboxName, $checkboxValue, $checkboxId, $headCss = null, $cellCss = null)
    {
        $this->_rowSelectable = true;
        $col = new SelectableColumn();
        $col->getHead()->setAttribute('class', $headCss);
        $col->getCell()->setAttribute('class', $cellCss)
            ->getChild(0)->setAttribute(array(
            'name' => $checkboxName,
            'value' => $checkboxValue,
            'id' => $checkboxId));
        $this->addColumn($col);
        return $col;
    }

    public function addBooleanColumn($headText, $fieldName, $trueText, $falseText, $headCss = null, $cellCss = null)
    {
        $col = new BooleanColumn();
        $col->getHead()->setAttribute(array('class' => $headCss, 'text' => $headText));
        $col->getCell()->setAttribute('class', $cellCss);
        $col->setFieldName($fieldName)->setTrueText($trueText)->setFalseText($falseText);
        $this->addColumn($col);
        return $col;
    }

    public function addButtonColumn($headText, $cellText, $script, $headCss = null, $cellCss = null)
    {
        $col = new ButtonColumn();
        $col->getHead()->setAttribute(array('class' => $headCss, 'text' => $headText));
        $col->getCell()->setAttribute('class', $cellCss)
            ->getChild(0)->setAttribute(array('text' => $cellText, 'onclick' => $script));
        $this->addColumn($col);
        return $col;
    }

    public function addLinkButtonColumn($headText, $cellText, $script, $headCss = null, $cellCss = null)
    {
        $col = new ButtonColumn('link');
        $col->getHead()->setAttribute(array('class' => $headCss, 'text' => $headText));
        $col->getCell()->setAttribute('class', $cellCss)
            ->getChild(0)->setAttribute(array('text' => $cellText, 'onclick' => $script));
        $this->addColumn($col);
        return $col;
    }

    public function render()
    {
        $head = array();
        $filters = array();
        $body = array();
        $footer = array();
        $hasFilter = false;

        foreach ($this->_columns as $col) {
            $head[] = $col->renderHead();
        }

        foreach ($this->_columns as $col) {
            $s = $col->renderFilter();
            if(!empty($s)){
                $hasFilter = true;
            }
            $filters[] = '<th>'.$s.'</th>';
        }

        foreach ($this->_dataSource as $index => $dataRow) {
            $cells = array();
            foreach ($this->_columns as $col) {
                $cells[] = $col->renderCell($dataRow, $index);
            }
            $body[] = $cells;
        }

        $result = array($this->renderBegin());
        $result[] = '<thead><tr>';
        $result[] = implode('', $head);
        $result[] = '</tr>';
        if($hasFilter){
            $result[] = '<tr class="filter">'.implode('', $filters).'</tr>';
        }
        $result[] = '</thead><tbody>';
        foreach ($body as $row) {
            $result[] = '<tr>' . implode('', $row) . '</tr>';
        }
        $result[] = '</tbody>';
        $result[] = $this->renderEnd();
        return implode('', $result);
    }
}