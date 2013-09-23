<?php




$value1 = 5;
$value2 = 10;

$variableString = "$value1
$value2";


assert($variableString, "5
10");



$multiLineString = "This is a string
That spans two lines";

$value = 10;

assert($value, 10);


testEnd();
