<?php
namespace Components\System;

use Components\System\Models\SettingModel;
use Toy\Web\Template;
use Toy\Web\Application;

class Listener
{
    static public function webOnStart($app, $argument)
    {
        $setting = SettingModel::load(1);
        Application::$context->system = $setting;
        Template::addHelper('system', $setting);
    }
}
