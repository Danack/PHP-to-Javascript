<?php

class Adult {

	static $adultStaticVar;

	public $adultClassVar;

	function	adultMethod(){

		self::$adultStaticVar = 123;

		$this->adultClassVar = 123;

		echo "This is an adult method. \n";
	}
}


class Child extends Adult {

	static $childStaticVar;

	public $childInstanceVar;

	function	childMethod(){

		self::$childStaticVar = 123;

		$this->childInstanceVar = 123;

		echo "This is a child method. \n";
	}
}


$child = new Child();

$child->childMethod();

$child->adultMethod();




?>