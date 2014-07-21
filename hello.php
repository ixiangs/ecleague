<?php

class A
{

    static public function test()
    {
        $cls = get_called_class();
        $ref = new \ReflectionClass($cls);
        $ref->setStaticPropertyValue('name', 'i am a');
    }
}

class B extends A
{

}

B::test();
echo B::$name;

//class A
//{
//    static public function test()
//    {
//        echo get_called_class() . "\n";
//    }
//}
//
//class B extends A
//{
//
//}
//
//class C{
//    public $name;
//}
//
//$c1 = new C();
//$c2 = $c1;
//
//$c1->name = 'ronald';
//$c2->name = 'hello';
//
//var_dump($c1);
//var_dump($c2);
