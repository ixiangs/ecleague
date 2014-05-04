<?php
namespace Core\Attrs\Model;

use Ecleague\Tops;
use Toy\Orm;

class AttributeGroupModel extends Orm\Model{

    const TABLE_NAME = '{t}attrs_attribute_group';

    public function getAttributes(){
        if(!array_key_exists('attributes', $this->data)){
            $this->data['attributes'] = Tops::loadModel('attrs/attribute')
                ->find('{t}attrs_r_group_attribute.*')
                ->join('{t}attrs_r_group_attribute', '{t}attrs_r_group_attribute.attribute_id', '{t}attrs_attribute.id')
                ->eq('{t}attrs_r_group_attribute.group_id', $this->id)
                ->asc('{t}attrs_r_group_attribute.position')
                ->load();
        }
        return $this->data['attributes'];
    }

    public function assignAttribute(array $attributes, $db){
        $ds = new \Toy\Data\Sql\DeleteStatement('{t}attrs_r_group_attribute');
        $db->delete($ds->eq('group_id', $this->id));
        foreach($attributes as $attribute){
            $us = new \Toy\Data\Sql\InsertStatement('{t}attrs_r_group_attribute', array(
                'group_id'=>$this->id,
                'attribute_id'=>$attribute['id'],
                'position'=>$attribute['position']
            ));
            $db->insert($us);
        }
        return $this;
    }
}

Orm\Model::register('Core\Attrs\Model\AttributeGroupModel', array(
    'table'=>AttributeGroupModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\SerializeProperty::create('name')->setNullable(false),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)->setNullable(false),
        Orm\SerializeProperty::create('memo'),
    )
));