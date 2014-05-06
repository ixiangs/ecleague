<?php
namespace Core\Attrs\Model;

use Ecleague\Tops;
use Toy\Orm;

class AttributeSetModel extends Orm\Model{

    const TABLE_NAME = '{t}attrs_attribute_set';

    public function getGroups(){
        if(!array_key_exists('groups', $this->data)){
            $this->data['groups'] = Tops::loadModel('attrs/attributeGroup')
                ->find()
                ->join('{t}attrs_r_set_group', '{t}attrs_r_set_group.group_id', '{t}attrs_attribute_group.id')
                ->eq('{t}attrs_r_set_group.set_id', $this->id)
                ->asc('{t}attrs_r_set_group.position')
                ->load();
        }
        return $this->data['groups'];
    }

    public function assignGroup(array $attributes, $db){
        $ds = new \Toy\Db\DeleteStatement('{t}attrs_r_set_group');
        $db->delete($ds->eq('set_id', $this->id));
        foreach($attributes as $attribute){
            $us = new \Toy\Db\InsertStatement('{t}attrs_r_set_group', array(
                'set_id'=>$this->id,
                'group_id'=>$attribute['id'],
                'position'=>$attribute['position']
            ));
            $db->insert($us);
        }
        return $this;
    }

    public function getGroupAttributes()
    {
        if(count($this->getGroupIds()) == 0){
            return array();
        }

        $groups = $this->getGroups();
        $groupIds = $groups->toArray(function($item){
           return array(null, $item->getId());
        });
        $attributes = Tops::loadModel('attrs/attribute')
            ->find()
            ->join('{t}attrs_r_group_attribute', '{t}attrs_r_group_attribute.attribute_id', '{t}attrs_attribute.id')
            ->in('{t}attrs_r_group_attribute.group_id', $groupIds)
            ->asc('{t}attrs_r_group_attribute.group_id', '{t}attrs_r_group_attribute.position')
            ->load();

        foreach ($groups as $group) {
            $ids = $group->getAttributeIds();
            $groupAttrs = array();
            foreach ($attributes as $attribute) {
                if ($attribute->getGroupId() == $group->getId()) {
                    $groupAttrs[] = $attribute;
                }
            }
            $group->attributes = $groupAttrs;
        }

        return $groups;
    }

}

Orm\Model::register('Core\Attrs\Model\AttributeSetModel', array(
    'table'=>AttributeSetModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\StringProperty::create('code')->setNullable(false),
        Orm\SerializeProperty::create('name')->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)->setNullable(false),
        Orm\SerializeProperty::create('memo')->setNullable(false)
    )
));