<?php

class A
{
    static public function test()
    {
        echo get_called_class() . "\n";
    }
}

class B extends A
{

}

B::test();

