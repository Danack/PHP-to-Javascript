<?php


class ExampleJSON{

	public 		$objectID;
	public 		$name;
	public 		$value;

	static function	factory($jsonString){
		$data = json_decode($jsonString);

		$object = new self();

		//Shamoan
		foreach ($data AS $flipflop => $value){
			$object->   $flipflop = $value;
		}

		return $object;
	}


	function	__construct($objectID = FALSE, $name = 'UnknownName', $value = "UnknownValue"){
		$this->objectID = $objectID;
		$this->name 	= $name;
		$this->value 	= $value;
	}



	function	toJSON(){
		$params = array(
			'objectID'		=>	$this->objectID,
			'name'			=> 	$this->name,
			'value'			=>	$this->value,
		);

		return json_encode($params);
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