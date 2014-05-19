<?php
namespace Ixiangs\Attrs;

use Toy\Db\Helper;
use Toy\Orm;

class EntityModel extends Orm\Model
{

    public function getGroups()
    {
        if (!array_key_exists('groups', $this->data)) {
            $this->data['groups'] = GroupModel::find()
                ->join(Constant::TABLE_R_SET_GROUP,
                    Constant::TABLE_R_SET_GROUP . '.group_id',
                    Constant::TABLE_GROUP . '.id')
                ->eq(Constant::TABLE_R_SET_GROUP . '.set_id', $this->id)
                ->asc(Constant::TABLE_R_SET_GROUP . '.position')
                ->load();
        }
        return $this->data['groups'];
    }

    public function assignGroup(array $attributes, $db)
    {
        Helper::delete(Constant::TABLE_R_SET_GROUP)
            ->eq('set_id', $this->id)
            ->execute($db);
        foreach ($attributes as $attribute) {
            Helper::insert(Constant::TABLE_R_SET_GROUP, array(
                'set_id' => $this->id,
                'group_id' => $attribute['id'],
                'position' => $attribute['position']
            ))->execute($db);
        }
        return $this;
    }

    public function getGroupAttributes()
    {
        if (count($this->getGroupIds()) == 0) {
            return array();
        }

        $groups = $this->getGroups();
        $groupIds = $groups->toArray(function ($item) {
            return array(null, $item->getId());
        });
        $attributes = AttributeModel::find()
            ->join(Constant::TABLE_R_GROUP_ATTRIBUTE,
                Constant::TABLE_R_GROUP_ATTRIBUTE . 'attribute_id',
                Constant::TABLE_ATTRIBUTE . 'id')
            ->in(Constant::TABLE_R_GROUP_ATTRIBUTE . '.group_id', $groupIds)
            ->asc(Constant::TABLE_R_GROUP_ATTRIBUTE . '.group_id', Constant::TABLE_R_GROUP_ATTRIBUTE . '.position')
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

EntityModel::register(array(
    'table' => Constant::TABLE_ENTITY,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('component_id')->setNullable(false),
        Orm\StringProperty::create('code')->setNullable(false),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\ListProperty::create('attribute_ids'),
        Orm\BooleanProperty::create('enabled')->setDefaultValue(true)->setNullable(true),
        Orm\StringProperty::create('memo')
    ),
    'relations' => array(
        Orm\Relation::childrenRelation('groups', '\Ixiangs\Attrs\GroupModel', 'entity_id')
    )
));