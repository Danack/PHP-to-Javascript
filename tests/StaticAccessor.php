<?php


class Class1 {
    public static $instance;
    public $foo = "Class1";
}



class Class2{
    function __construct(){

        echo "shamoan";
        
        Class1::$instance->foo = 'Class2';
    }
}


$test = new Class1();
$test2 = new Class2();



assert($test->foo, 'Class2');




?> 