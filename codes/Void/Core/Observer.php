<?php
namespace Void\Core;

use Toy\Web\Template;
use Toy\Web\Application;

class Observer
{
    static public function webOnStart($app, $argument)
    {
        $setting = SettingModel::load(1);
        Application::$context->system = $setting;
        Template::addHelper('system', $setting);
    }
}
