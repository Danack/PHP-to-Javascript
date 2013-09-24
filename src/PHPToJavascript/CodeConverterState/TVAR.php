<?php

namespace PHPToJavascript;


class CodeConverterState_TVAR extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		// In js is impossible make private fields or methods
		$this->stateMachine->variableFlags |= DECLARATION_TYPE_PRIVATE;
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}

