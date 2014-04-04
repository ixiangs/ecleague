<?php
date_default_timezone_set('PRC');
define('DS', DIRECTORY_SEPARATOR);
define('TOY_PATH', dirname(__FILE__). DS. 'lib'. DS . 'Toy' . DS);
//define('LIB_PATH', ROOT_PATH . 'lib' . DS);
//set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH . PATH_SEPARATOR . PRI_PATH . 'components');


include_once TOY_PATH . 'Collection' . DS . 'TEnumerator.php';
include_once TOY_PATH . 'Collection' . DS . 'TList.php';
include_once TOY_PATH . 'Collection' . DS . 'ArrayList.php';

$a = new \Toy\Collection\ArrayList(array(1, 2 , 3, 4));

foreach($a as $i){
    print($i);
}


