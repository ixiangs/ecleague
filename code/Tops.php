<?php

final class Tops {

    public function loadModel($name){
        return \Toy\Loader::create(str_replace('/', '_model_', $name).'Model');
    }

    public function loadController($name){
        return \Toy\Loader::create(str_replace('/', '_model_', $name).'Model');
    }
} 