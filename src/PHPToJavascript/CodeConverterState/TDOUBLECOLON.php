<?php

namespace PHPToJavascript;

class CodeConverterState_TDOUBLECOLON extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->addJS('.');
		$this->stateMachine->addVariableFlags(DECLARATION_TYPE_CLASS);
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}





?>