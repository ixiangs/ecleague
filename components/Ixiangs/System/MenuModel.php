<?php
namespace Ixiangs\System;

use Toy\Db\Helper;
use Toy\Orm;

class MenuModel extends Orm\Model
{
    protected function beforeInsert($db)
    {
        if ($this->isEmptyData('parent_id')) {
            $this->setData('parent_id', 0);
        }
        return parent::beforeInsert($db);
    }

    protected function beforeUpdate($db)
    {
        return $this->beforeInsert($db);
    }

    public function updatePosition($sorts, $db = null)
    {
        $updates = array();
        foreach ($sorts as $index => $item) {
            $updates[] = Helper::update($this->tableName, array(
                'position' => $index + 1,
                'parent_id' => 0
            ))->eq('id', $item['id']);
            if (array_key_exists('children', $item)) {
                $this->createUpdatePositionStatement($item['children'], $item['id'], $updates);
            }
        }

        if (is_null($db)) {
            Helper::withTx(function ($db) use ($updates) {
                foreach ($updates as $u) {
                    $db->update($u);
                }
            });
        } else {
            foreach ($updates as $u) {
                $db->update($u);
            }
        }
    }

    private function createUpdatePositionStatement($items, $parentId, &$updates)
    {
        foreach ($items as $index => $item) {
            $updates[] = Helper::update($this->tableName, array(
                'position' => $index + 1,
                'parent_id' => $parentId
            ))->eq('id', $item['id']);
            if (array_key_exists('children', $item)) {
                $this->createUpdatePositionStatement($item['children'], $item['id'], $updates);
            }
        }
    }
}

MenuModel::register(array(
    'table' => Constant::TABLE_MENU,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('component_id'),
        Orm\IntegerProperty::create('parent_id')->setNullable(true),
        Orm\SerializeProperty::create('names')->setNullable(false),
        Orm\StringProperty::create('url'),
        Orm\IntegerProperty::create('position')->setDefaultValue(99),
        Orm\BooleanProperty::create('enabled')->setNullable(false)
    )
));