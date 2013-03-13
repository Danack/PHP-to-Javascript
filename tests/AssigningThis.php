<?php

function	paramTest($object, $otherVar){
	return $object;
}


class TestClass{

	var $five = 5;

	function	getThis(){
		return $this;
	}

	function	getThis2(){
		return paramTest($this, 'ignored var');
	}

	function	getThis3(){
		$returnValue = $this;
		return $returnValue;
	}

	function	getClassName(){
		$className = get_class($this);
		return $className;
	}

	function	getValue(){
		return $this->five;
	}


}

$testClass = new TestClass();

assert($testClass->getThis(), $testClass);
assert($testClass->getThis2(), $testClass);
assert($testClass->getThis3(), $testClass);
assert($testClass->getClassName(), 'TestClass');
assert($testClass->getValue(), 5);

testEnd();

?>