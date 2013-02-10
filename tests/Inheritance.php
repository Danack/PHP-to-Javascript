<?php

class Adult {

	static $adultStaticVar;

	public $adultClassVar;

	function	adultMethod(){

		self::$adultStaticVar = 123;

		$this->adultClassVar = 123;

		echo "This is an adult method. \n" + $this->adultClassVar + " " + self::$adultStaticVar;
	}
}


class Child extends Adult {

	static $childStaticVar;

	public $childInstanceVar;

	function	childMethod(){

		self::$childStaticVar = 123;

		$this->childInstanceVar = 123;

		// TODO - Adult static variable needs to be accessed via Adult.adultStaticVar not
		// Child.adultStaticVar in the generated javascript. Which may be tricky.

		echo "This is a child method. \n" + $this->childInstanceVar + " " + self::$childStaticVar + " " +  + $this->adultClassVar + " " + self::$adultStaticVar;
	}
}


$child = new Child();

$child->childMethod();

$child->adultMethod();




?>