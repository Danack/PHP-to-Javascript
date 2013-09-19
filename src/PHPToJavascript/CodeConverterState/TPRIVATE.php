<?php

namespace PHPToJavascript;


class CodeConverterState_TPRIVATE extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		// In js is impossible make private fields or methods
		$this->stateMachine->variableFlags |= DECLARATION_TYPE_PUBLIC;
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}

