<?php

require_once('functions.php');


trait JSONFactory{

	static function	factory($jsonString){
		$data = json_decode($jsonString);

		$object = new static();

		foreach ($data AS $key => $value){
			$object->$key = $value;
		}

		return $object;
	}

	function	toJSON(){
		return json_encode_object($this);
	}
}

class ExampleJSON{

	use JSONFactory;

	public 		$objectID;
	public 		$name;
	public 		$value;

	function	__construct($objectID = FALSE, $name = 'UnknownName', $value = "UnknownValue"){
		$this->objectID = $objectID;
		$this->name 	= $name;
		$this->value 	= $value;
	}

	function test(){
		echo "name = ".$this->name." value = ".$this->value;
	}
}

$testObject = new ExampleJSON(1, "First", "Testing");

$json = $testObject->toJSON();

$duplicate = ExampleJSON::factory($json);

$duplicate->test();


?>