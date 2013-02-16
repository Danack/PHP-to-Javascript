<?php

$total = 0;

for ($i=0 ; $i<5 ; $i++){
	$total += $i;
}

//JS assert(total, 15);

$value = '123';
$delta = 123;
$value = /*value*/$value + $delta;

//JS assert(value, 246);

$result = str_pad($value, 6, '0', STR_PAD_LEFT);

echo $result;

?>