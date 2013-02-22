<?php


class TestClass{

	function	toJSON(){
		$className = get_class($this);
		$testing = $this;

		return json_encode_object($testing, $className);
	}

	function	test2(){
		$className = get_class($this);
		return json_encode_object($this, $className);
	}

	function	test3(){
		$className = get_class($this);
		return json_encode_object($this, $className, $this);
	}
}

?>