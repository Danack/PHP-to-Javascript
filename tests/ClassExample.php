<?php

class ClassExample {


	public	$value = null;
	public 	$testArray = null;
	var $local = 9;

	static public 	$staticVar = 0;

	function	__construct($initialValue){
		$this->value = $initialValue;

		$this->testArray = array();
		$this->testArray[0] = 1;
		$this->testArray[1] = 2;
		$this->testArray[2] = 3;
	}


	function testStatic(){
		$currentValue = self::$staticVar;
		self::$staticVar++;
		return $currentValue;
	}

	function	addValue($value){
		$this->value += $value;
	}

	function	getArrayValue(){
		$result = 0;

		foreach($this->testArray as $testValue){
			$result += $testValue;
		}

		return $result;
	}

	function	getArrayValueWithIndex(){
		$result = 0;

		foreach($this->testArray as $key => $testValue){
			$result += $testValue;
		}

		return $result;
	}

	private function privateFunction(){
		return 5;
	}

	public function publicAccess(){
		return $this->privateFunction();
	}

	public function testFunctionStatic(){
		static $countUnique = 0;
		$countUnique++;

		return $countUnique;
	}

	public function getLocal(){
		global $local;
		return $local;
	}

}



$classExample = new ClassExample(5);

$classExample->addValue(5);

assert($classExample->value, 10);

assert($classExample->getArrayValue(), 6);

assert($classExample->getArrayValueWithIndex(), 6);


$classExample->testStatic();
$classExample->testStatic();
$result = $classExample->testStatic();


//Called two times, but value is only incremented twice
assert($result, 2);




/*
private is impossible in plain js. see https://github.com/Danack/PHP-to-Javascript/issues/33
$privateAccessed = false;
$exceptionCaught = false;
try{
	//Yes IDE - I know this isn't allowed.
	///** @noinspection PhpIllegalArrayKeyTypeInspection * /

	// @SuppressWarnings
	$classExample->privateFunction();
	$privateAccessed = true;
}
catch(Exception $error){
	//This correct - the private function should not be callable.
	$exceptionCaught = true;
}

assert($privateAccessed, false);
assert($exceptionCaught, true);
*/
$value = $classExample->publicAccess();
assert($value, 5);


$value = $classExample->getLocal();
assert($value, 9);


$classExample->testFunctionStatic();
$classExample->testFunctionStatic();
$classExample->testFunctionStatic();
$value = $classExample->testFunctionStatic();

assert($value, 4);

testEnd();

?>