<?php


function 	testSwitchFunction($name, $value = FALSE){

	$result = FALSE;

	switch($name){

		case('output'):{
			$result =  'putout';
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
			throw new Exception("Unknown option");
		}
	}

	return $result;
}


//JS if(typeof assert === undefined){
//JS 	function assert(var1, var2){
//JS		if(var1 != var2){
//JS 			alert("assert failed " + var1 + " != " + var2 );
//JS			throw new Error("assert failed");
//JS		}
//JS    }
//JS }



//JS assert(testSwitchFunction('output') == 'putout');
//JS assert(testSwitchFunction('custom', 'bar') == 'barr');

?>