<?php

require_once 'TraitInclude.php';

//require_once('functions.php');



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

assert($duplicate->name == "First", TRUE);
assert($duplicate->value == "Testing", TRUE);

testEnd();

?>