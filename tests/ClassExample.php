<?php

class ClassExample {

	static var $testStatic = 1;
	var		$tags = null;

	function testFunction(){
		json_encode_object($this->varName, 'SomeString');
	}

	function __construct(){
		static $count = 0;
		$this->tags = array();
		$this->tags[] = $count;
	}
}


echo ClassExample::$testStatic;

$classExample1 = new ClassExample();

foreach($classExample1->tags as $tag){
	echo $tag."\n";
}

$classExample2 = new ClassExample();

foreach($classExample2->tags as $tag){
	echo $tag."\n";
}

?>