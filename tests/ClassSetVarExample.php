<?php

class ClassSetVarExample {

	static protected $dataMap = [
		array('photoID', 'id'),
		['owner', 'owner'],
//		['secret', 'secret'],
//		['serverID', 'server'],
//		['farmID', 'farm'],
//		['title', 'title', 'unindex' => '_content'],
//		['description', 'description', 'unindex' => '_content', 'optional' => true],
	];

	public 		$instanceTestArray = array(
		1, 2, 3
	);

	static public 		$staticTestArray = array(
		1, 2, 3, 4
	);


	var		$value = null;

	function __construct(){

		echo "lol";


	}

	function	getInstanceArrayValue(){
		$result = 0;

		foreach($this->instanceTestArray as $testValue){
			$result += $testValue;
		}

		return $result;
	}

	function	getStaticArrayValue(){
		$result = 0;

		foreach(self::$staticTestArray as $testValue){
			$result += $testValue;
		}

		return $result;
	}
}



$classExample = new ClassSetVarExample();
assert($classExample->getInstanceArrayValue(), 6);
assert($classExample->getStaticArrayValue(), 10);
testEnd();

?>