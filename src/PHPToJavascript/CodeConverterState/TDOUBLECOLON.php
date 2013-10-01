<?php

namespace PHPToJavascript;

class CodeConverterState_TDOUBLECOLON extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		$this->stateMachine->addVariableFlags(DECLARATION_TYPE_CLASS);
        $this->stateMachine->addVariableFlags(DECLARATION_TYPE_STATIC);

        $this->stateMachine->addJS('.');
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



