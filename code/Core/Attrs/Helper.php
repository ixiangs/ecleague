<?php
namespace Core\Attrs;

use Ecleague\Tops;

class Helper
{
    static public function getAttributeTree($code)
    {
        $set = Tops::loadModel('attrs/attributeSet')
            ->find()->eq('code', $code)
            ->limit(1)->load()
            ->getFirst();

        $groups = Tops::loadModel('attrs/attributeGroup')
            ->find()->in('id', $set->getGroupIds())
            ->load();

        $attrIds = array();
        foreach ($groups as $group) {
            $attrIds = array_merge($attrIds, $group->getAttributeIds());
        }
        $attributes = Tops::loadModel('attrs/attribute')
            ->find()->in('id', $attrIds)
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

        $set->groups = $groups;

        return $set;
    }
}