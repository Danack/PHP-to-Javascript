<?php

class StaticTest {

	public static	$publicStaticClassVar = 0;
	public 			$publicInstanceClassVar = 0;

	private static 	$privateStaticClassVar = 0;
	private  		$privateInstanceClassVar = 0;

	//var $instanceVar = 0;

	static function staticMethod(){
		self::$publicStaticClassVar++;
		self::$privateStaticClassVar++;
	}

	function	instanceMethod(){
		$this->publicInstanceClassVar++;
		$this->privateInstanceClassVar++;
	}

	function methodWithStatic(){
		static $staticVar = 0;
		$staticVar++;
		echo $staticVar;
	}

	function	debugOutput(){
		echo "publicStaticClassVar ".$this->publicStaticClassVar;
		echo "publicInstanceClassVar ".$this->publicInstanceClassVar;

		echo "privateStaticClassVar ".$this->privateStaticClassVar;
		echo "privateInstanceClassVar ".$this->privateInstanceClassVar;
	}
}


?>