<?php
namespace Core\Attrs\Model;

use Ecleague\Tops;
use Toy\Orm;

class AttributeSetModel extends Orm\Model{

    const TABLE_NAME = '{t}attrs_attribute_set';

    public function getGroupAttributes()
    {
        if(count($this->getGroupIds()) == 0){
            return array();
        }

        $groups = Tops::loadModel('attrs/attributeGroup')
            ->find()->in('id', $this->group_ids)
            ->load();

        $attrIds = array();
        foreach ($groups as $group) {
            $attrIds = array_merge($attrIds, $group->getAttributeIds());
        }
        $attributes = Tops::loadModel('attrs/attribute')
            ->find()->in('id', $attrIds)->eq('enabled', true)
            ->load();

        foreach ($groups as $group) {
            $ids = $group->getAttributeIds();
            $groupAttrs = array();
            foreach ($attributes as $attribute) {
                if (in_array($attribute->getId(), $ids)) {
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
        Orm\StringProperty::create('code')->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setNullable(false),
        Orm\SerializeProperty::create('name')->setNullable(false),
        Orm\SerializeProperty::create('group_ids'),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\SerializeProperty::create('memo')->setNullable(false)
    )
));