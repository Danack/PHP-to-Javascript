<?php


/**
 * Return a JSON string for all of the public variables of an object.
 * @param $object
 * @return string
 */
function json_encode_object($object){

	$params = array();

	foreach($object as $key => $value){
		$params[$key] = $value;
	}

	return json_encode($params);
}


?>