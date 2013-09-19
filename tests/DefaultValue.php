<?php

function	getTotal($value1, $value2 = 5){
	return $value1 + $value2;
}

$test = function(){

	$mathTotal = getTotal(5);

	assert($mathTotal, 10);

	testEnd();
};
$test();
?>