<?php

class StaticTest {

	public static $staticClassVar = 0;

	var $instanceVar = 0;

	function staticMethod(){
		$this->staticClassVar++;
		echo $this->staticClassVar;
	}

	function	instanceMethod(){
		$this->instanceVar++;
		echo $this->instanceVar;
	}

	function methodWithStatic(){
		static $staticVar = 0;
		$staticVar++;
		echo $staticVar;
	}

}


?>