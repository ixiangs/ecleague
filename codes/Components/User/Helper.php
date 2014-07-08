<?php
/**
 * Created by PhpStorm.
 * User: ronald.xian
 * Date: 14-7-8
 * Time: 上午11:48
 */

namespace Components\User;


use Components\User\Models\AccountModel;

class Helper {

    static public function getNormalAccouns(){
        return AccountModel::find()
                ->eq(AccountModel::propertyToField('type'), Constant::TYPE_NORMAL)
                ->load();
    }
} 