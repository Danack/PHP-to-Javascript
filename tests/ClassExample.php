<?php

class ClassExample {


	var		$value = null;
	var 	$testArray = null;

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

/* TODO - make exception conversion work.



$privateAccessed = false;

try{
	$classExample->privateFunction();
	privateAccessed = true;
}
catch(Exception $error){
	//This correct - the private function should not be callable.
}

assert(privateAccessed, false);

*/



$value = $classExample->publicAccess();

assert($value, 5);


?>