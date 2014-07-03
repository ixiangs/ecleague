<?php
namespace Toy\Html;

use Toy\Util\StringUtil;

class Grid extends Element
{

    protected $columns = array();
    protected $dataSource = null;
    protected $checkboxVisible = false;
    protected $checkboxValue = '';
    protected $sequenceVisible = true;
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
        $this->dataSource = $value;
        return $this;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function addColumn($col)
    {
        $this->columns[] = $col;
        return $this;
    }

    public function createColumn($headText = null, $headCss = null, $cellCss = null)
    {
        $col = new GridColumn($headText, $headCss, $cellCss);
        $this->addColumn($col);
        return $col;
    }

    public function getCheckboxVisible()
    {
        return $this->checkboxVisible;
    }

    public function setCheckboxVisible($value)
    {
        $this->checkboxVisible = $value;
        return $this;
    }

    public function getCheckboxValue()
    {
        return $this->checkboxValue;
    }

    public function setCheckboxValue($value)
    {
        $this->checkboxValue = $value;
        return $this;
    }

    public function getSequenceVisible()
    {
        return $this->sequenceVisible;
    }

    public function setSequenceVisible($value)
    {
        $this->sequenceVisible = $value;
        return $this;
    }

    public function addLabelColumn($headText, $cellText, $headCss = null, $cellCss = null)
    {
        $span = new Element('span', array('text' => $cellText));
        $col = $this->createColumn($headText, $headCss, $cellCss);
        $col->getCell()->addChild($span);
        return $col;
    }

    public function addStatusColumn($headText, $cellText, $options, $headCss = null, $cellCss = null)
    {
        $span = new StatusText(array('text' => $cellText));
        $span->setItems($options);
        $col = $this->createColumn($headText, $headCss, $cellCss);
        $col->getCell()->addChild($span);
        return $col;
    }

    public function addLinkColumn($headText, $cellText, $link, $headCss = null, $cellCss = null)
    {
        $span = new Element('a', array('text' => $cellText, 'href' => $link));
        $col = $this->createColumn($headText, $headCss, $cellCss);
        $col->getCell()->addChild($span);
        return $col;
    }

    public function addButtonColumn($headText, $cellText, $script, $headCss = null, $cellCss = null)
    {
        $span = new Element('a', array(
            'href'=>'javascript:',
            'text' => $cellText));
        $span->setEvent('click', $script);
        $col = $this->createColumn($headText, $headCss, $cellCss);
        $col->getCell()->addChild($span);
        return $col;
    }

//    public function addCheckboxColumn($checkboxName, $checkboxValue, $checkboxId, $headText, $headCss = null, $cellCss = null)
//    {
//        $span = new Element('input', array(
//            'type'=>'checkbox',
//            'id'=>$checkboxId,
//            'name'=>$checkboxName,
//            'value'=>$checkboxValue));
//        $col = $this->createColumn($headText, $headCss, $cellCss);
//        $col->getCell()->addChild($span);
//        return $col;
//    }

//    public function addBooleanColumn($headText, $fieldName, $trueText, $falseText, $headCss = null, $cellCss = null)
//    {
//        $col = new BooleanColumn();
//        $col->getHead()->setAttribute(array('class' => $headCss, 'text' => $headText));
//        $col->getCell()->setAttribute('class', $cellCss);
//        $col->setFieldName($fieldName)->setTrueText($trueText)->setFalseText($falseText);
//        $this->addColumn($col);
//        return $col;
//    }
//
//    public function addButtonColumn($headText, $cellText, $script, $headCss = null, $cellCss = null)
//    {
//        $col = new ButtonColumn();
//        $col->getHead()->setAttribute(array('class' => $headCss, 'text' => $headText));
//        $col->getCell()->setAttribute('class', $cellCss)
//            ->getChild(0)->setAttribute(array('text' => $cellText, 'onclick' => $script));
//        $this->addColumn($col);
//        return $col;
//    }
//
//    public function addLinkButtonColumn($headText, $cellText, $script, $headCss = null, $cellCss = null)
//    {
//        $col = new ButtonColumn('link');
//        $col->getHead()->setAttribute(array('class' => $headCss, 'text' => $headText));
//        $col->getCell()->setAttribute('class', $cellCss)
//            ->getChild(0)->setAttribute(array('text' => $cellText, 'onclick' => $script));
//        $this->addColumn($col);
//        return $col;
//    }

//    public function addHidden($id, $name, $value = null)
//    {
//        $f = new Element('input', array('type' => 'hidden', 'id' => $id, 'name' => $name, 'value' => $value));
//        $this->hiddens[] = $f;
//        return $f;
//    }

    public function render()
    {
        $heads = array();
        $filters = array();
        $body = array();
//        $footer = array();

        if ($this->checkboxVisible) {
            $heads[] = '<th class="checkbox-head"></th>';
        }

        if ($this->sequenceVisible) {
            $heads[] = '<th class="sequence-head">#</th>';
        }

        foreach ($this->columns as $col) {
            $heads[] = $col->renderHead();
        }

        foreach ($this->columns as $col) {
            $s = $col->renderFilter();
            if (!empty($s)) {
                $filters[] = '<th class="filter-head">' . $s . '</th>';
            }
        }

        foreach ($this->dataSource as $index => $dataRow) {
            $cells = array();
            if ($this->checkboxVisible) {
                $cells[] = '<td class="checkbox-cell"><input type="checkbox" value="' . StringUtil::substitute($this->checkboxValue, $dataRow) . '"/></td>';
            }

            if ($this->sequenceVisible) {
                $cells[] = '<td class="sequence-cell"><span>' . ($index + 1) . '</span></td>';
            }
            foreach ($this->columns as $col) {
                $cells[] = $col->renderCell($dataRow, $index);
            }
            $body[] = $cells;
        }

        $result = array($this->renderBegin());
        $result[] = '<thead><tr>' . implode('', $heads) . '</tr>';
        if (count($filters) > 0) {
            if ($this->checkboxVisible) {
                array_unshift($filters, '<th class="checkbox-head"></th>');
            }

            if ($this->sequenceVisible) {
                array_unshift($filters, '<th class="checkbox-head"></th>');
            }
            $result[] = '<tr class="row-filter">' . implode('', $filters) . '</tr>';
        }
        $result[] = '</thead><tbody>';
        foreach ($body as $row) {
            $result[] = '<tr>' . implode('', $row) . '</tr>';
        }
        $result[] = '</tbody>' . $this->renderEnd();
        return implode('', $result);
    }
}