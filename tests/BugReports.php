<?php


//https://github.com/Danack/PHP-to-Javascript/issues/39

class Foo{
    
    private $foo = 0;
    
    function __construct(){
        $this->setFoo(5);
    }
    function setFoo($foo){
        $this->foo = $foo;
    }

    function getFoo(){
        return $this->foo;
    }
}

$foo = new Foo();

assert($foo->getFoo(), 5);




//https://github.com/Danack/PHP-to-Javascript/issues/44
//Embedded variables

$value1 = 5;

$value2 = "Hello " . $value1 . " there";
assert($value2, "Hello 5 there");

$value3 = "Hello ${value1} there";
assert($value3, "Hello 5 there");

function greet($name) {
    return "Hello ${name}!";
}

assert(greet("Bob"), "Hello Bob!");


//https://github.com/Danack/PHP-to-Javascript/issues/14
//Modulus doesn't work

$p2 = 8;
$step = 6;

$p2 -= ($p2 % $step);

assert($p2, 6);

//*************************************************************
//*************************************************************

//https://github.com/Danack/PHP-to-Javascript/issues/15
$countValue = 4;
$countValue--;

//assert($countValue, 3);

$countValue--;

assert($countValue, 2);


//https://github.com/Danack/PHP-to-Javascript/issues/48



function add($value1, $value2 = -1) {
    return $value1 + $value2;
}


assert(add(5, 5), 10);

assert(add(5), 4);



testEnd();



?>