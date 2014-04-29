<?php
namespace Core\Attrs\Model;

use Ecleague\Tops;
use Toy\Orm;

class AttributeGroupModel extends Orm\Model{

    const TABLE_NAME = '{t}attrs_attribute_group';

    public function getAttributes(){
        if(!array_key_exists('attributes', $this->data)){
            $this->data['attributes'] = Tops::loadModel('attrs/attribute')->find()
                                            ->in('id', $this->getAttributeIds())
                                            ->load();
        }
        return $this->data['attributes'];
    }
}

Orm\Model::register('Core\Attrs\Model\AttributeGroupModel', array(
    'table'=>AttributeGroupModel::TABLE_NAME,
    'properties'=>array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\SerializeProperty::create('name')->setNullable(false),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\IntegerProperty::create('set_id'),
        Orm\ListProperty::create('attribute_ids'),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)->setNullable(false),
        Orm\SerializeProperty::create('memo'),
    )
));