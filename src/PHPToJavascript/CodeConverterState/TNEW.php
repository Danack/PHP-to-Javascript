<?php

namespace PHPToJavascript;

class CodeConverterState_TNEW  extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->addJS('new');
		$this->stateMachine->addVariableFlags(DECLARATION_TYPE_NEW);
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}





?>