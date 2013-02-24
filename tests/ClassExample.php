<?php

class ClassExample {


	var		$value = null;
	var 	$testArray = null;

	function	__construct($initialValue){
		$this->value = $initialValue;

		$this->testArray = array();
		$this->testArray[0] = 1;
		$this->testArray[1] = 2;
		$this->testArray[2] = 3;
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
}



$classExample = new ClassExample(5);

$classExample->addValue(5);

assert($classExample->value, 10);

assert($classExample->getArrayValue(), 6);

assert($classExample->getArrayValueWithIndex(), 6);


?>