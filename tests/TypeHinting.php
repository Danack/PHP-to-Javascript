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


$test = function(){
	$test3 = test1(null);
	assert($test3, 1);



	$testArray = array(1, 2, 3);
	$test4 = test2($testArray);
	assert($test4, 6);

	testEnd();
};
$test();

?>