<?php
namespace Codes\System\Models;

use Codes\System\Constant;
use Toy\Orm;

class MenuModel extends Orm\Model
{
    protected function beforeInsert($db)
    {
        if ($this->isEmptyProperty('parent_id')) {
            $this->setData('parent_id', 0);
        }
        return parent::beforeInsert($db);
    }

    protected function beforeUpdate($db)
    {
        return $this->beforeInsert($db);
    }

    static public function sort($sorts, $db)
    {
        $statements = array();
        foreach ($sorts as $index=>$item) {
            $statements[] = Helper::update(
                Constant::TABLE_MENU, array(
                    'position' => $index + 1,
                    'parent_id' => $item['parent_id']? $item['parent_id']: 0
                ))->eq('id', $item['id']);
        }

        foreach ($statements as $u) {
            $db->update($u);
        }
    }
}

MenuModel::registerMetadata(array(
    'table' => Constant::TABLE_MENU,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('component_id'),
        Orm\IntegerProperty::create('parent_id')->setNullable(true),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\StringProperty::create('url'),
        Orm\IntegerProperty::create('position')->setDefaultValue(99),
        Orm\BooleanProperty::create('enabled')->setNullable(false),
        Orm\ListProperty::create('behavior_codes')
    )
));