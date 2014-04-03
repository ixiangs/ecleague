<?php
class Hello{

    public static function say(){
        print 'i say hello';
    }
}

function getHello(){
    return 'Hello';
}

$h = 'Hello';
getHello()->say();