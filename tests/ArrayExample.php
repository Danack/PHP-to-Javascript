<?php



$dataMap = [
	array('photoID', 'id'),
	['owner', 'owner'],
];

$gah = [1, 2];


$test = $gah[0];

$intArray2 = array(
	1,
	1 => 2,
	2 => 3,
	'subArray' => [
		[1],
		2,
		3 => 3
	],
);


//JS function	array_push_value(array, value){
//JS
//JS 	for(var x=0 ; x<1000 ; x++){
//JS 		if(array.hasOwnProperty(x) == false){
//JS 			array[x] = value;
//JS 			return;
//JS 		}
//JS 	}
//JS
//JS 	throw new Error("Can't push onto array - it is too large.");
//JS }


function sumArray($intArray){

	$total = 0;

	foreach($intArray as $value){
		$total += $value;
	}

	return $total;
}

//*************************************************************
//*************************************************************

//Shamoan

$pushArray = array();

array_push($pushArray, 1);
array_push($pushArray, 2);
array_push($pushArray, 3);

$value = sumArray($pushArray);

assert($value, 6);

//*************************************************************
//*************************************************************

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

//*************************************************************
//*************************************************************

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



//$value = sumArray($intArray);
$value = sumArray($intArray['subArray']);

assert($value, 6);

//*************************************************************
//*************************************************************

class ArrayTestClass{

	var $noteID = 123;

	function getTestArray(){
		$params = array('noteID' => $this->noteID);
		return $params;
	}
}

$arrayTestClass = new ArrayTestClass();

$testArray = $arrayTestClass->getTestArray();

$value = sumArray($testArray);

assert($value, 123);


testEnd();

?>