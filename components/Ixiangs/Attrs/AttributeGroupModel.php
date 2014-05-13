<?php
namespace Ixiangs\Attrs;

use Toy\Db\Helper;
use Toy\Orm;

class AttributeGroupModel extends Orm\Model
{

    const TABLE_NAME = '{t}attrs_attribute_group';

    public function getAttributes()
    {
        if (!array_key_exists('attributes', $this->data)) {
            $this->data['attributes'] = AttributeModel::find()
                ->join(Constant::TABLE_R_GROUP_ATTRIBUTE,
                    Constant::TABLE_R_GROUP_ATTRIBUTE . '.attribute_id',
                    Constant::TABLE_ATTRIBUTE . '.id')
                ->eq(Constant::TABLE_R_GROUP_ATTRIBUTE . '.group_id', $this->id)
                ->asc(Constant::TABLE_R_GROUP_ATTRIBUTE . '.position')
                ->load();
        }
        return $this->data['attributes'];
    }

    public function assignAttribute(array $attributes, $db)
    {
        Helper::delete(Constant::TABLE_R_GROUP_ATTRIBUTE)
            ->eq('group_id', $this->id)
            ->execute($db);
        foreach ($attributes as $attribute) {
            Helper::insert(Constant::TABLE_R_GROUP_ATTRIBUTE, array(
                'group_id' => $this->id,
                'attribute_id' => $attribute['id'],
                'position' => $attribute['position']
            ))->execute($db);
        }
        return $this;
    }
}

AttributeGroupModel::register(array(
    'table' => Constant::TABLE_ATTRIBUTE_GROUP,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\SerializeProperty::create('name')->setNullable(false),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)->setNullable(false),
        Orm\SerializeProperty::create('memo'),
    )
));