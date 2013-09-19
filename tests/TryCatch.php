<?php

$exceptionCaught = false;


function testFunction($value1){
	adddlert("Welcome guest $value1 !");
}

$exceptionMessage = false;

try{
	$result = testFunction(5);
	echo "Result is $result";
}
catch(Exception $e){
	$exceptionCaught = true;
	//echo "Exception caught ".$e->getMessage();
	$exceptionMessage = $e->getMessage();
}

assert($exceptionCaught, true);
assertGreater(strlen($exceptionMessage), 5);


$thrownExceptionCaught = false;
try{
	throw new Exception("What is this?");
}
catch(Exception $e){
	//echo "Exception caught ".$e->getMessage();
	$thrownExceptionCaught = true;
}

assert($thrownExceptionCaught, true);

testEnd();

?>