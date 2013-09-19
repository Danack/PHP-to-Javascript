<?php

class Adult {

	var		$endOffset = FALSE;

	static $adultStaticVar;

	public $adultClassVar;

	public $isExtended = false;

	function	adultValue(){
		$this->adultClassVar = 123;
		return $this->adultClassVar;
	}
}


class Child extends Adult {

	static $childStaticVar;

	public $childInstanceVar;

	function __construct(){
		$this->isExtended = true;
	}

	function	childValue(){

	//	self::$childStaticVar = 123;

		$this->childInstanceVar = 123;

		// TODO - Adult static variable needs to be accessed via Adult.adultStaticVar not
		// Child.adultStaticVar in the generated javascript. Which may be tricky.

	//	echo "This is a child method. \n" + $this->childInstanceVar + " " + self::$childStaticVar + " " +  + $this->adultClassVar + " " + self::$adultStaticVar;

		return 12345;
	}
}

$adultOnly = new Adult();

assert($adultOnly->adultValue(), 123);
assert($adultOnly->isExtended, false);

$child = new Child();

assert($child->childValue(), 12345);
assert($child->adultValue(), 123);
assert($child->isExtended, true);


testEnd();

?>