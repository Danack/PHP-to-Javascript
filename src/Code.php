<?php

define('DATABASE_TYPE', 'MySQL');

define("CHARACTER_TYPE", 'UTF8');

echo "Database type is ".DATABASE_TYPE;

define('FILE_TYPE_IMAGE', 'Is an image');

function test1($param1){

	static $var1 = 0;

	$var1++;

	$var2 = "Hello";

	echo $var2." ".$var1." ".FILE_TYPE_IMAGE;
}

$var1 = 'test';

echo $var1;

function test2($param1){
	$var1 = "Hello";
	echo $var1 . $param1;
}


class Test{

	var $contentID;
	var $text;

	private $id = 12345;

	function	__construct($contentID, $text){
		$this->contentID = $contentID;
		$this->text = $text;

		$this->id = 12345;

		echo "FILE_TYPE_IMAGE is ".FILE_TYPE_IMAGE;
	}

	function	getThumbnailURL($var1){
		$output = "/proxy/";
		$output .= $this->contentID;
		$output .= "/thumbnail/";
		$output .= $this->text;

		$output .= $var1;

		return urlencode($output);
	}
}






?>