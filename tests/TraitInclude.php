<?php


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

		$className = get_class($this);

		return json_encode_object($this, $className);
	}
}

testEnd();

?>