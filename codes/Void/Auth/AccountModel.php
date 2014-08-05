<?php
namespace Void\Auth;

use Toy\Event;
use Toy\Orm;
use Toy\Util\EncryptUtil;

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
            return VOID_AUTH_ERROR_ACCOUNT_PASSWORD;
        }

        if ($m->comparePassword($old)) {
            $res = Helper::update(VOID_AUTH_TABLE_ACCOUNT, array('password' => EncryptUtil::encryptPassword($new)))
                ->eq('id', $id)
                ->execute($db);
            if (!$res) {
                return VOID_AUTH_ERROR_UNKNOW;
            }
        }
        return true;
    }

    static public function login($username, $password)
    {
        $m = static::find()->eq('username', $username)->load()->getFirst();

        if (empty($m)) {
            return array(VOID_AUTH_ERROR_ACCOUNT_NOT_FOUND, null);
        }
        if (!$m->comparePassword($password)) {
            return array(VOID_AUTH_ERROR_ACCOUNT_PASSWORD, null);
        }

        switch ($m->status) {
            case VOID_AUTH_STATUS_ACCOUNT_NONACTIVATED:
                return array(VOID_AUTH_ERROR_ACCOUNT_NONACTIVATED, null);
            case VOID_AUTH_STATUS_ACCOUNT_DISABLED:
                return array(VOID_AUTH_ERROR_ACCOUNT_DISABLED, null);
        }

        $behaviorCodes = array();
        $roleCodes = array();
        $roleIds = $m->getRoleIds();

        if (count($roleIds) > 0) {
            if ($roleIds[0] == '*') {
                $roleCodes = '*';
                $behaviorCodes = '*';
            } else {
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
        }

        $result = new Identity($m->id, $m->username, $m->getDomains(), $roleCodes, $behaviorCodes);
        Event::dispatch(VOID_AUTH_EVENT_ACCOUNT_LOGIN, $m, $result);
        return array(true, $result);
    }
}

AccountModel::registerMetadata(array(
        'table' => VOID_AUTH_TABLE_ACCOUNT,
        'properties' => array(
            Orm\IntegerProperty::create('id')->setPrimaryKey(true)->setAutoIncrement(true),
            Orm\StringProperty::create('username')->setUnique(true)->setUpdateable(false),
            Orm\StringProperty::create('password')->setUpdateable(false),
            Orm\StringProperty::create('email'),
            Orm\IntegerProperty::create('status'),
            Orm\ListProperty::create('domains'),
            Orm\ListProperty::create('role_ids'),
            Orm\IntegerProperty::create('group_id')
        ))
);
