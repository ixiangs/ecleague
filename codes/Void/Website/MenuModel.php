<?php
namespace Void\Website;

use Toy\Orm, Toy\Orm\Db;

class MenuModel extends Orm\Model
{

    protected function beforeInsert($db)
    {
        $this->insertOrdering($db);
        return parent::beforeInsert($db);
    }

    protected function beforeUpdate($db)
    {
        if ($this->propertyIsChanged('parent_id')) {
            $this->insertOrdering($db);
            return parent::beforeUpdate($db);
        }

        if ($this->propertyIsChanged('ordering')) {
            $co = $this->ordering;
            $so = $this->originalData['ordering'];
            if ($so < $co) {
                self::find()
                    ->eq('parent_id', $this->parent_id)
                    ->le('ordering', $co)
                    ->ge('ordering', $co)
                    ->load()
                    ->each(function ($item) use ($db) {
                        Db\Helper::update(Constant::TABLE_MENU, array(
                            'ordering' => $item->ordering + 1
                        ))
                            ->eq('id', $item->id)
                            ->execute($db);
                    });
            } elseif ($so > $co) {
                self::find()
                    ->eq('parent_id', $this->parent_id)
                    ->ge('ordering', $co)
                    ->lt('ordering', $so)
                    ->load()
                    ->each(function ($item) use ($db) {
                        Db\Helper::update(Constant::TABLE_MENU, array(
                            'ordering' => $item->ordering + 1
                        ))
                            ->eq('id', $item->id)
                            ->execute($db);
                    });
            }

        }
    }

    private function insertOrdering($db)
    {
        if ($this->ordering == 0) {
            $max = self::find(false)
                ->select('ordering')
                ->eq('parent_id', $this->parent_id)
                ->desc('ordering')
                ->limit(1)
                ->fetchFirstValue();
            $this->ordering = $max + 1;
        } else {
            self::find()
                ->eq('parent_id', $this->parent_id)
                ->ge('ordering', $this->ordering)
                ->load()
                ->each(function ($item) use ($db) {
                    Db\Helper::update(Constant::TABLE_MENU, array(
                        'ordering' => $item->ordering + 1
                    ))
                        ->eq('id', $item->id)
                        ->execute($db);
                });
        }
    }
}

MenuModel::registerMetadata(array(
    'table' => Constant::TABLE_MENU,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('website_id')->setNullable(false)->setUpdateable(false),
        Orm\IntegerProperty::create('account_id')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('type_id')->setNullable(false)->setUpdateable(false),
        Orm\StringProperty::create('parent_id')->setNullable(false)->setDefaultValue(0),
        Orm\StringProperty::create('title')->setNullable(false),
        Orm\StringProperty::create('link')->setNullable(false),
        Orm\StringProperty::create('icon'),
        Orm\IntegerProperty::create('status')->setNullable(false)->setDefaultValue(true),
        Orm\IntegerProperty::create('ordering')->setNullable(false)
    )
));