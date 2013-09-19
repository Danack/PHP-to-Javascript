<?php


function 	testSwitchFunction($name, $value = FALSE){

	$result = FALSE;

	switch($name){

		case('output'):{
			$result =  'output';
			break;
		}

		case('silent'):{
			$result =  'notloud';
			break;
		}

		case('custom'):{
			$result = $value;
			break;
		}

		default:{
			$result = 'Unknown';
		}
	}

	return $result;
}


assert(testSwitchFunction('output'), 'output');
assert(testSwitchFunction('custom', 'bar'), 'bar');
assert(testSwitchFunction('shamoan'), 'Unknown');

testEnd();

?>