<?php




namespace PHPToJavascript;

class CodeConverterState_TTRY  extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->addJS('try');
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}





?>