<?php

class A
{
    static public function test()
    {
        echo __CLASS__ . "\n";
    }
}

class B extends A
{
    static public function test()
    {
        echo __CLASS__ . "\n";
    }
}

B::test();

