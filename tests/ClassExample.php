<?php

class ClassExample {


	var		$value = null;

	function	__construct($initialValue){
		$this->value = $initialValue;
	}

	function	addValue($value){
		$this->value += $value;
	}
}



$classExample = new ClassExample(5);

$classExample->addValue(5);

assert($classExample->value, 10);



?>