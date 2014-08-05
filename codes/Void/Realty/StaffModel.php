<?php
namespace Void\Realty;

use Toy\Orm, Toy\Orm\Db;

class StaffModel extends Orm\Model
{
    public function delete($db = null)
    {
        $cdb = $db ? $db : Db\Helper::openDb();
        $this->beforeDelete($cdb);
        $result = Db\Helper::update($this->metadata->getTableName(), array('deleted'=>1))
            ->eq($this->metadata->getPrimaryKey()->getName(), $this->getIdValue())
            ->execute($db);
        $this->afterDelete($cdb);
        return $result;
    }
}

StaffModel::registerMetadata(array(
    'table' => VOID_REALTY_TABLE_STAFF,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\IntegerProperty::create('uptown_id')->setNullable(false),
        Orm\StringProperty::create('name')->setNullable(false),
        Orm\StringProperty::create('mobile'),
        Orm\StringProperty::create('phone'),
        Orm\StringProperty::create('position'),
        Orm\IntegerProperty::create('deleted')->setDefaultValue(0)
    )
));