<?php
namespace Ecleague;

use Toy\Loader;

final class Tops {

    public function loadModel($name, array $data = array()){
        return Loader::create(str_replace('/', '\\model\\', $name).'Model', $data);
    }

    public function loadController($name){
        return Loader::create(str_replace('/', '_model_', $name).'Model');
    }
} 