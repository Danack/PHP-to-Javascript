<?php


$content = new Content(1234, 'image');


function	testFunction(){
	echo "Shamoan";
}

function testFunction2(){
	static $testVar = 0;
	echo "testVar = ".$testVar."\r\n";
	$testVar++;
}


function testFunction3(){
	$testVar = 0;
	$testVar++;
	echo "testVar = ".$testVar."\r\n";
}

?>