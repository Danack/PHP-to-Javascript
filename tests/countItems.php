<?php

//This test requires PHP.js

$testArray = array('foo', 'bar', 'zot');


$countValues = count($testArray);


assert($countValues, 3);

testEnd();


?> 