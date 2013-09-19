<?php

class StaticTest {

	static $staticClassVar = 0;

	var $instanceVar = 0;

	static function staticMethod(){
		self::$staticClassVar++;
		//echo $this->staticClassVar;
	}

	function	instanceMethod(){
		$this->instanceVar++;
		//echo $this->instanceVar;
	}

	function methodWithStatic(){
		static $staticVar = 0;
		$staticVar++;
		//echo $staticVar;
		return $staticVar;
	}
}


StaticTest::staticMethod();


$staticTest = new StaticTest();

$staticTest->instanceMethod();

$staticTest->methodWithStatic();
$staticTest->methodWithStatic();
$staticTest->methodWithStatic();

assert(StaticTest::$staticClassVar, 1);
assert($staticTest->instanceVar, 1);
assert($staticTest->methodWithStatic(), 4);

testEnd();

?>