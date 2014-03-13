<?php
/**
 * Created by PhpStorm.
 * User: ixiangs
 * Date: 14-3-12
 * Time: 下午11:58
 */

namespace Toys\Data\Sql;

abstract class WhereStatement extends BaseStatement{

    protected $conditions = array();

    protected function __construct(){}

    public function eq($field, $value){
        $this->conditions[] = array($field, $value, 'eq');
        return $this;
    }

    public function gt($field, $value){
        $this->conditions[] = array($field, $value, 'gt');
        return $this;
    }

    public function lt($field, $value){
        $this->conditions[] = array($field, $value, 'lt');
        return $this;
    }

    public function ge($field, $value){
        $this->conditions[] = array($field, $value, 'ge');
        return $this;
    }

    public function le($field, $value){
        $this->conditions[] = array($field, $value, 'le');
        return $this;
    }

    public function ne($field, $value){
        $this->conditions[] = array($field, $value, 'ne');
        return $this;
    }

    public function like($field, $value){
        $this->conditions[] = array($field, $value, 'like');
        return $this;
    }

    public function in($field, $value){
        $this->conditions[] = array($field, $value, 'in');
        return $this;
    }

    public function notIn($field, $value){
        $this->conditions[] = array($field, $value, 'notin');
        return $this;
    }

    public function between($field, $value){
        $this->conditions[] = array($field, $value, 'between');
        return $this;
    }
} 