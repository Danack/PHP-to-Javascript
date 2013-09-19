<?php

class StaticTest {

	public static	$publicStaticClassVar = 0;
	public 			$publicInstanceClassVar = 0;

	private static 	$privateStaticClassVar = 0;
	private  		$privateInstanceClassVar = 0;

	static function staticMethod(){
		self::$publicStaticClassVar++;
		self::$privateStaticClassVar++;
	}

	function	instanceMethod(){
		$this->publicInstanceClassVar++;
		$this->privateInstanceClassVar++;
	}
}

testEnd();

?>