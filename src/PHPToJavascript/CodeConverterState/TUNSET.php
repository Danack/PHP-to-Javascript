<?php



namespace PHPToJavascript;

class CodeConverterState_TUNSET  extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		//This is pretty fragile. Delete only works on objects where the variable is deletable
		//http://perfectionkills.com/understanding-delete/#property_attributes

		$this->stateMachine->addJS('delete');
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}





?>