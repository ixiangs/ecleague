<?php
/**
 * Created by PhpStorm.
 * Auth: ixiangs
 * Date: 14-3-12
 * Time: 下午11:58
 */

namespace Toy\Data\Sql;

abstract class WhereStatement extends BaseStatement{

    protected $conditions = array();

    protected function __construct(){}

    public function getConditions(){
        return $this->conditions;
    }

    public function orNext(){
        if(count($this->conditions) > 0){
            $e = end($this->conditions);
            if(!is_string($e)){
                $this->conditions[] = 'OR';
            }
        }
        return $this;
    }

    public function andNext(){
        if(count($this->conditions) > 0){
            $e = end($this->conditions);
            if(!is_string($e)){
                $this->conditions[] = 'AND';
            }
        }
        return $this;
    }

    public function eq($field, $value){
        $this->andNext();
        $this->conditions[] = array('eq', $field, $value);
        return $this;
    }

    public function gt($field, $value){
        $this->andNext();
        $this->conditions[] = array('gt', $field, $value);
        return $this;
    }

    public function lt($field, $value){
        $this->andNext();
        $this->conditions[] = array('lt', $field, $value);
        return $this;
    }

    public function ge($field, $value){
        $this->andNext();
        $this->conditions[] = array('ge', $field, $value);
        return $this;
    }

    public function le($field, $value){
        $this->andNext();
        $this->conditions[] = array('le', $field, $value);
        return $this;
    }

    public function ne($field, $value){
        $this->andNext();
        $this->conditions[] = array('ne', $field, $value);
        return $this;
    }

    public function like($field, $value){
        $this->andNext();
        $this->conditions[] = array('like', $field, $value);
        return $this;
    }

    public function in($field, $value){
        $this->andNext();
        $this->conditions[] = array('in', $field, $value);
        return $this;
    }

    public function notIn($field, $value){
        $this->andNext();
        $this->conditions[] = array('notin', $field, $value);
        return $this;
    }

    public function isNull($field){
        $this->andNext();
        $this->conditions[] = array('isnull', $field);
        return $this;
    }

    public function notNull($field){
        $this->andNext();
        $this->conditions[] = array('notnull', $field);
        return $this;
    }

    public function between($field, $value){
        $this->andNext();
        $this->conditions[] = array('between', $field, $value);
        return $this;
    }
} 