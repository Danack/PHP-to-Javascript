<?php

class ClassExample {

	var		$tags = array();



	function __construct(){
		static $count = 0;
		$this->tags[] = $count;
	}
}


$classExample1 = new ClassExample();

foreach($classExample1->tags as $tag){
	echo $tag."\n";
}

$classExample2 = new ClassExample();

foreach($classExample2->tags as $tag){
	echo $tag."\n";
}

?>