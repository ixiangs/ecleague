<?php
namespace Core\Admin\Model;

use Toy\Data\Helper;
use Toy\Data\Sql\UpdateStatement;
use Toy\Orm;

class MenuModel extends Orm\Model
{
    const TABLE_NAME = '{t}admin_menu';

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
            $update = new UpdateStatement(self::TABLE_NAME, array(
                'position' => $index + 1,
                'parent_id' => 0
            ));
            $update->eq('id', $item['id']);
            $updates[] = $update;
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
            $update = new UpdateStatement(self::TABLE_NAME, array(
                'position' => $index + 1,
                'parent_id' => $parentId
            ));
            $update->eq('id', $item['id']);
            $updates[] = $update;
            if (array_key_exists('children', $item)) {
                $this->createUpdatePositionStatement($item['children'], $item['id'], $updates);
            }
        }
    }
}

Orm\Model::register('Core\Admin\Model\MenuModel', array(
    'table' => MenuModel::TABLE_NAME,
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