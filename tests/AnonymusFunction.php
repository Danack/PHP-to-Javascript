<?php

$test = function($arg=5){
	return $arg;
};

class Foo{
	public $func;
	function __construct(){
		$this->func = function($arg){
			return $arg;
		};
	}
	function foo(){
		return function($arg){
			return $arg;
		};
	}
}

$foo = new Foo();

assert($test(5), 5);
assert($foo->func(5), 5);
$test = $foo->foo();
assert($test(5), 5);

testEnd();