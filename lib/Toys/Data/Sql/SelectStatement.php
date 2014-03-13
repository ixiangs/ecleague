<?php
namespace Toys\Data\Sql;

class SelectStatement extends WhereStatement
{

    protected $fields = array();
    protected $from = null;
//    private $where = array();
    protected $joins = array();
    protected $orderby = array();
    protected $offset = 0;
    protected $limit = 0;

    public function select()
    {
        $args = func_get_args();
        if (func_num_args() == 1 && is_array($args[0])) {
            $this->_fields = array_merge($this->_fields, $args[0]);
        } else {
            foreach ($args as $v) {
                $this->_fields[] = $v;
            }
        }
        return $this;
    }

    public function from($from)
    {
        $this->from = $from;
        return $this;
    }

    public function joinInner($table, $left, $right)
    {
        $this->joins[] = array('inner', $table, $left, $right);
        return $this;
    }

    public function joinLeft($table, $left, $right)
    {
        $this->joins[] = array('left', $table, $left, $right);
        return $this;
    }

    public function joinRight($table, $left, $right)
    {
        $this->joins[] = array('right', $table, $left, $right);
        return $this;
    }

//    public function andFilter($condition, $value)
//    {
//        if (count($this->where) > 0) {
//            $this->where[] = 'and';
//        }
//        $this->where[] = array($condition, $value);
//        return $this;
//    }
//
//    public function orFilter($condition, $value = NULL)
//    {
//        if (count($this->where) > 0) {
//            $this->where[] = 'or';
//        }
//        $this->where[] = array($condition, $value = NULL);
//        return $this;
//    }

    public function asc()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            $this->orderby[$arg] = 'ASC';
        }
        return $this;
    }

    public function desc()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            $this->orderby[$arg] = 'DESC';
        }
        return $this;
    }

    public function limit($count, $offset = 0)
    {
        $this->offset = $offset;
        $this->limit = $count;
        return $this;
    }

//    public function resetSelect()
//    {
//        $this->fields = array();
//        return $this;
//    }
//
//    public function resetJoin()
//    {
//        $this->joins = array();
//        return $this;
//    }

//    public function resetWhere()
//    {
//        $this->where = array();
//        return $this;
//    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getJoins()
    {
        return $this->joins;
    }

//    public function getWhere()
//    {
//        return $this->where;
//    }

    public function getOrderBy()
    {
        return $this->orderby;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getLimit()
    {
        return $this->limit;
    }

}
