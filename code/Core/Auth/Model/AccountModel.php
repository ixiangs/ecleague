<?php
namespace Core\Auth\Model;

use Core\Auth\Identity;
use Toy\Data\Helper, Toy\Data\Sql\UpdateStatement;
use Toy\Orm;
use Toy\Util\EncryptUtil;
use Toy\Data\Db;

class AccountModel extends Orm\Model
{

    const TABLE_NAME = '{t}auth_account';

    const ERROR_NOT_FOUND = 1;
    const ERROR_PASSWORD = 2;
    const ERROR_DISABLED = 3;
    const ERROR_NONACTIVATED = 4;
    const ERROR_REPEATED = 5;
    const ERROR_UNKNOW = 99;

    const STATUS_ACTIVATED = 1;
    const STATUS_NONACTIVATED = 2;
    const STATUS_DISABLED = 3;

    const LEVEL_ADMINISTRATOR = 1;
    const LEVEL_NORMAL = 2;

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
            print_r($this->data);
            die();
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
        if ($m->status == self::STATUS_NONACTIVATED) {
            return array(self::ERROR_NONACTIVATED, null);
        }
        if ($m->status == self::STATUS_DISABLED) {
            return array(self::STATUS_DISABLED, null);
        }

        $behaviorCodes = array();
        $roleCodes = array();
        $roleIds = $m->getRoleIds();

        if (count($roleIds) > 0) {
            $roles = \Tops::loadModel('auth/role')->find()->in('id', $roleIds)->eq('enabled', 1)
                ->load()
                ->toArray(function($item){
                    return array($item->getCode(), $item->getBehaviorIds());
                });
            $roleCodes = array_keys($roles);
            if (count($roleCodes) > 0) {
                $behaviorIds = array();
                foreach ($roles as $code => $bidArr) {
                    if (!empty($bidArr)) {
                        $behaviorIds = array_merge($behaviorIds, $bidArr);
                    }
                }
                $behaviorCodes = \Tops::loadModel('auth/behavior')->find()
                                    ->in('id', $behaviorIds)
                                    ->eq('enabled', 1)
                                    ->select('code')
                                    ->load()
                                    ->toArray(function($item){
                                        return array(null, $item->getCode());
                                    });
            }
        }

        return array(true, new Identity($m->id, $m->username, $m->level, $roleCodes, $behaviorCodes));
    }

    public function activate()
    {
        return static::create(array('id' => $this->id, 'status' => self::STATUS_ACTIVATED))->update(array('status'));
    }

    public function freeze()
    {
        return static::create(array('id' => $this->id, 'status' => self::STATUS_DISABLED))->update(array('status'));
    }

}

Orm\Model::register('Core\Auth\Model\AccountModel', array(
        'table' => AccountModel::TABLE_NAME,
        'properties' => array(
            Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
            Orm\StringProperty::create('username')->setUnique(true)->setUpdateable(false),
            Orm\StringProperty::create('password')->setUpdateable(false),
            Orm\StringProperty::create('email'),
            Orm\IntegerProperty::create('status'),
            Orm\IntegerProperty::create('level'),
            Orm\ListProperty::create('role_ids')
        ))
);
