<?php

//https://github.com/Danack/PHP-to-Javascript/issues/14
//Modulus doesn't work

$p2 = 8;
$step = 6;

$p2 -= ($p2 % $step);

assert($p2, 6);

//*************************************************************
//*************************************************************

//https://github.com/Danack/PHP-to-Javascript/issues/15
$count = 4;
$count--;

assert($count, 3);

$count--;

assert($count, 2);


testEnd();

?>