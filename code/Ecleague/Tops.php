<?php
namespace Ecleague;

final class Tops {

    public function loadModel($name, array $data = array()){
        return \Toy\Loader::create(str_replace('/', '\\model\\', $name).'Model', $data);
    }

    public function loadController($name){
        return \Toy\Loader::create(str_replace('/', '_model_', $name).'Model');
    }
} 