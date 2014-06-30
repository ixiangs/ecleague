<?php
namespace Toy\Orm\Db;

class SelectStatement extends WhereStatement
{

    protected $fields = array();
    protected $table = null;
    protected $joins = array();
    protected $orderby = array();
    protected $offset = 0;
    protected $limit = 0;

    public function __construct($table = null, array $fields = array())
    {
        $this->table = $table;
        $this->fields = $fields;
    }

    public function select()
    {
        $args = func_get_args();
        if (func_num_args() == 1 && is_array($args[0])) {
            $this->fields = array_merge($this->fields, $args[0]);
        } else {
            foreach ($args as $v) {
                $this->fields[] = $v;
            }
        }
        return $this;
    }

    public function from($from)
    {
        $this->table = $from;
        return $this;
    }

    public function join($leftField, $rightField, $type = 'inner')
    {
        $table = explode('.', $leftField)[0];
        $this->joins[] = array($type, $table, $leftField, $rightField);
        return $this;
    }

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

    public function resetSelect()
    {
        $this->fields = array();
        return $this;
    }

    public function resetJoin()
    {
        $this->joins = array();
        return $this;
    }

    public function resetWhere()
    {
        $this->conditions = array();
        return $this;
    }

    public function resetLimit()
    {
        $this->limit = 0;
        $this->offset = 0;
        return $this;
    }

    public function resetOrderby()
    {
        $this->orderby = array();
        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getJoins()
    {
        return $this->joins;
    }

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

    public function executeCount($db = null)
    {
        $cdb = is_null($db) ? Helper::openDb() : $db;
        $res = $cdb->select($this->resetSelect()->resetLimit()->resetOrderby()->select('count(*)'));
        return $res->getFirstValue();
    }

    public function execute($db = null)
    {
        $cdb = is_null($db) ? Helper::openDb() : $db;
        return $cdb->select($this);
    }
}
