<?php
namespace Core\Admin\Model;

use Toy\Db\Helper, Toy\Db\UpdateStatement;
use Toy\Orm;
use Toy\Util\EncryptUtil;
use Toy\Db;

class AdministratorModel extends Orm\Model
{

    const TABLE_NAME = '{t}admin_administrator';

    const ERROR_NOT_FOUND = 1;
    const ERROR_PASSWORD = 2;
    const ERROR_DISABLED = 3;
    const ERROR_REPEATED = 4;
    const ERROR_UNKNOW = 99;

    protected function beforeInsert()
    {
        $this->password = EncryptUtil::encryptPassword($this->password);
    }

    public function comparePassword($other)
    {
        return $this->password == EncryptUtil::encryptPassword($other);
    }

    public function validate()
    {
        if (!$this->id) {
            $res = parent::validate();
            if ($res) {
                if (AccountModel::checkUnique('username', $this->username)) {
                    return self::ERROR_REPEATED;
                }
            }
            return $res;
        }
        return parent::validate();

    }

    public function modifyPassword($id, $old, $new, $db = null)
    {
        $m = self::load($id);
        if (!$m->comparePassword($old)) {
            return self::ERROR_PASSWORD;
        }

        if ($m->comparePassword($old)) {
            $cdb = $db ? $db : Helper::openDb();
            $us = new UpdateStatement(self::TABLE_NAME, array('password' => EncryptUtil::encryptPassword($new)));
            $us->eq('id', $id);
            if (!$cdb->update($us)) {
                return self::ERROR_UNKNOW;
            }
        }
        return true;
    }

    public function login($username, $password)
    {
        $m = self::find()->eq('username', $username)->load()->getFirst();
        if (empty($m)) {
            return array(self::ERROR_NOT_FOUND, null);
        }

        if (!$m->comparePassword($password)) {
            return array(self::ERROR_PASSWORD, null);
        }

        if (!$m->enabled) {
            return array(self::ERROR_DISABLED, null);
        }

        return array(true, $m);
    }

}

Orm\Model::register('Core\Admin\Model\AdministratorModel', array(
        'table' => AdministratorModel::TABLE_NAME,
        'properties' => array(
            Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
            Orm\StringProperty::create('username')->setUnique(true)->setUpdateable(false),
            Orm\StringProperty::create('password')->setUpdateable(false),
            Orm\StringProperty::create('email'),
            Orm\BooleanProperty::create('enabled')
        ))
);
