<?php
namespace Ixiangs\User;

use Ixiangs\System;

class Helper
{

    static public function getBehaviors($inclueComponent = true)
    {
        $find = BehaviorModel::find();
        if ($inclueComponent) {
            $find->select(
                    System\Constant::TABLE_COMPONENT . '.name as component_name',
                    Constant::TABLE_BEHAVIOR . '.*')
                ->join(System\Constant::TABLE_COMPONENT,
                    System\Constant::TABLE_COMPONENT . '.id',
                    Constant::TABLE_BEHAVIOR . '.component_id')
                ->asc(Constant::TABLE_BEHAVIOR .'.component_id',
                      Constant::TABLE_BEHAVIOR .'.code');
        }
        return $find->load();
    }
}