<?php


function	testUnset($testArray){

	if(array_key_exists('index2', $testArray) == TRUE){
		//Could do sanity check on type here.
		unset($testArray['index2']);
	}
	return $testArray;
}

function sumArray($intArray){

	$total = 0;

	foreach($intArray as $value){
		$total += $value;
	}

	return $total;
}


$array = array(
	'index1' => 1,
	'index2' => 2,
	'index3' => 3,
);



$unsetArray = testUnset($array);


$result = sumArray($unsetArray);


assert($result, 4);

testEnd();

?>