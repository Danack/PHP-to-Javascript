<?php

require_once('functions.php');

trait JSONFactory{

	static function	factory($jsonString){
		$data = json_decode($jsonString);

		$object = new static();

		//Shamoan
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
		return "name = ".$this->name." value = ".$this->value;
	}
}

$testObject = new ExampleJSON(1, "First", "Testing");

$json = $testObject->toJSON();

$duplicate = ExampleJSON::factory($json);


//JS if(typeof assert === undefined){
//JS 	function assert(var1, var2){
//JS		if(var1 != var2){
//JS 			alert("assert failed " + var1 + " != " + var2 );
//JS			throw new Error("assert failed");
//JS		}
//JS 		testsPassed++;
//JS    }
//JS }

assert($duplicate->name == "First", TRUE);
assert($duplicate->value == "Testing", TRUE);


?>