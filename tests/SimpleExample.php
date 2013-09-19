<?php

$test = function(){
	$target = "world";

	$greeting = "Hello $target!";

	assert($greeting, "Hello world!");

	$total = 0;

	for ($i=1 ; $i<=5 ; $i++){
		$total += $i;
	}

	assert(total, 15);

	$value = '123';
	$delta = 123;
	$value = /*value*/$value + $delta;

	assert(value, 246);

	$result = str_pad($value, 6, '0', STR_PAD_LEFT);

	assert($result, '000246');


	$testArray = array(
		1, 2, 3
	);

	function testFunction($testArray){

		$result = 0;

		foreach($testArray as $test){
			$result += $test;
		}

		return $result;
	}

	$value = testFunction($testArray);

	assert($value, 6);

	assert(eval('typeof Function.prototype.toString'), "function");

	$testGlobal= function(){
		global $foo1,$foo2;
	};
	$strFunc = $testGlobal->toString();
	assert($strFunc->indexOf('global') || $strFunc->indexOf('foo1') || $strFunc->indexOf('foo2') || $strFunc->indexOf(';'), -1);

	$testGlobal2= function(){
		global $foo1,$foo2,$foo4,$foo5,$foo6,$window;
		$foo3 = $foo2;
		$foo3 = eval($foo4);
		$foo3 = $foo4 + $foo5;
		$foo3 = $window;
		return $foo6;
	};
	assert($testGlobal2(),5);

	testEnd();
};
$test();

?>