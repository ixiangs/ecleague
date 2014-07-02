<?php
namespace Codes\User\Models;

use Toy\Orm;
use Toy\Orm\Helper;
use Toy\Util\EncryptUtil;
use Codes\User\Identity;
use Codes\User\Constant;

class AccountModel extends Orm\Model
{
    protected function beforeInsert()
    {
        $this->password = EncryptUtil::encryptPassword($this->password);
    }

    public function comparePassword($other)
    {
        return $this->password == EncryptUtil::encryptPassword($other);
    }

//    public function activate()
//    {
//        return static::create(array('id' => $this->id, 'status' => self::STATUS_ACTIVATED))->update(array('status'));
//    }
//
//    public function freeze()
//    {
//        return static::create(array('id' => $this->id, 'status' => self::STATUS_DISABLED))->update(array('status'));
//    }

    static public function updatePassword($id, $old, $new, $db = null)
    {
        $m = static::load($id);
        if (!$m->comparePassword($old)) {
            return Constant::ERROR_ACCOUNT_PASSWORD;
        }

        if ($m->comparePassword($old)) {
            $res = Helper::update(Constant::TABLE_ACCOUNT, array('password' => EncryptUtil::encryptPassword($new)))
                ->eq('id', $id)
                ->execute($db);
            if (!$res) {
                return Constant::ERROR_UNKNOW;
            }
        }
        return true;
    }

    static public function login($username, $password)
    {
        $m = static::find()->eq('username', $username)->load()->getFirst();

        if (empty($m)) {
            return array(Constant::ERROR_ACCOUNT_NOT_FOUND, null);
        }
        if (!$m->comparePassword($password)) {
            return array(Constant::ERROR_ACCOUNT_PASSWORD, null);
        }

        switch ($m->status) {
            case Constant::STATUS_ACCOUNT_NONACTIVATED:
                return array(Constant::ERROR_ACCOUNT_NONACTIVATED, null);
            case Constant::STATUS_ACCOUNT_DISABLED:
                return array(Constant::ERROR_ACCOUNT_DISABLED, null);
        }

        $behaviorCodes = array();
        $roleCodes = array();
        $roleIds = $m->getRoleIds();

        if (count($roleIds) > 0) {
            $roles = RoleModel::find()->in('id', $roleIds)->eq('enabled', 1)
                ->load()
                ->toArray(function ($item) {
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
                $behaviorCodes = BehaviorModel::find()
                    ->in('id', $behaviorIds)
                    ->eq('enabled', 1)
                    ->select('code')
                    ->load()
                    ->toArray(function ($item) {
                        return array(null, $item->getCode());
                    });
            }
        }

        return array(true, new Identity($m->id, $m->username, $m->type, $roleCodes, $behaviorCodes));
    }
}

AccountModel::registerMetadata(array(
    'table' => Constant::TABLE_ACCOUNT,
    'properties' => array(
        Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
        Orm\StringProperty::create('username')->setUnique(true)->setUpdateable(false),
        Orm\StringProperty::create('password')->setUpdateable(false),
        Orm\StringProperty::create('email'),
        Orm\IntegerProperty::create('status'),
        Orm\IntegerProperty::create('type'),
        Orm\ListProperty::create('role_ids')
    ))
);
