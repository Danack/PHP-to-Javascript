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
$Q = array(
	'ajax'=>function(){}
); //TODO: <- here will be bracket exchanged
Q::ajax(array(
			 'callback'=>function($foo){

			 }
		)); //TODO: <- here will be brackets exchanged

assert(5, 5);
testEnd();