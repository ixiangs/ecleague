<?php
/**
 * Created by PhpStorm.
 * User: ronald.xian
 * Date: 14-7-8
 * Time: 上午11:48
 */

namespace Void\Auth;


use Void\Auth\AccountModel;

class Helper {

    static public function getNormalAccouns(){
        return AccountModel::find()
                ->eq(AccountModel::propertyToField('type'), Constant::TYPE_ACCOUNT_FRONTEND)
                ->load();
    }
} 