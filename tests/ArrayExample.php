<?php

$testVar = 3;

$stringArray = array(
	'Hello',
	' ',
	'world',
);

$output = '';

foreach($stringArray as $string){
	$output .= $string;
}

assert($output, "Hello world");

$intArray = array(
    1,
	1 => 2,
	2 => 3,
	'subArray' => array(
		1,
		2,
		3 => 3
	),
);

function sumArray($intArray){

	$total = 0;

	foreach($intArray as $value){
		$total += $value;
	}

	return $total;
}

//$value = sumArray($intArray);
$value = sumArray($intArray['subArray']);

assert($value, 6);


?>