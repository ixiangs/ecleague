<?php
namespace Ixiangs\Attrs;

use Toy\Db\Helper;
use Toy\Orm;

class GroupModel extends Orm\Model
{
//    public function getAttributes()
//    {
//        if (!array_key_exists('attributes', $this->data)) {
//            $this->data['attributes'] = AttributeModel::find()
//                ->join(Constant::TABLE_R_GROUP_ATTRIBUTE,
//                    Constant::TABLE_R_GROUP_ATTRIBUTE . '.attribute_id',
//                    Constant::TABLE_ATTRIBUTE . '.id')
//                ->eq(Constant::TABLE_R_GROUP_ATTRIBUTE . '.group_id', $this->id)
//                ->asc(Constant::TABLE_R_GROUP_ATTRIBUTE . '.position')
//                ->load();
//        }
//        return $this->data['attributes'];
//    }
//
//    public function assignAttribute(array $attributes, $db)
//    {
//        Helper::delete(Constant::TABLE_R_GROUP_ATTRIBUTE)
//            ->eq('group_id', $this->id)
//            ->execute($db);
//        foreach ($attributes as $attribute) {
//            Helper::insert(Constant::TABLE_R_GROUP_ATTRIBUTE, array(
//                'group_id' => $this->id,
//                'attribute_id' => $attribute['id'],
//                'position' => $attribute['position']
//            ))->execute($db);
//        }
//        return $this;
//    }
}

GroupModel::register(array(
    'table' => Constant::TABLE_GROUP,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\IntegerProperty::create('entity_id')->setNullable(false),
        Orm\ListProperty::create('attribute_ids'),
        Orm\IntegerProperty::create('position')->setDefaultValue(99)
//        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)->setNullable(false),
//        Orm\SerializeProperty::create('memo'),
    )
));