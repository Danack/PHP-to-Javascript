<?php


function sumArray($intArray){

	$total = 0;

	foreach($intArray as $value){
		$total += $value;
	}

	return $total;
}

function test1(Form $form){
	return 1;
}


function test2(array $array){
	return sumArray($array);
}


$test1 = test1(null);
assert($test1, 1);



$testArray = array(1, 2, 3);
$test2 = test2($testArray);
assert($test2, 6);

testEnd();

?>