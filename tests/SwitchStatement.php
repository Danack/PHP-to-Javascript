<?php


function 	testSwitchFunction($name, $value){


	switch($name){

		case('output'):{
			echo "value is $value";
			break;
		}

		case('silent'):{
			break;
		}

		default:{
			//echo "Unknown option";
			throw new Exception("Unknown option");
		}
	}
}

testSwitchFunction('output', 'Shamoan');
testSwitchFunction('foo', 'bar');



?>