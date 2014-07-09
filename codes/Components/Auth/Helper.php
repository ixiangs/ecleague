<?php
/**
 * Created by PhpStorm.
 * User: ronald.xian
 * Date: 14-7-8
 * Time: 上午11:48
 */

namespace Components\Auth;


use Components\Auth\Models\AccountModel;

class Helper {

    static public function getNormalAccouns(){
        return AccountModel::find()
                ->eq(AccountModel::propertyToField('type'), Constant::TYPE_ACCOUNT_FRONTEND)
                ->load();
    }
} 