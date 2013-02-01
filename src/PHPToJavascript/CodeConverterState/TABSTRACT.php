<?php

namespace PHPToJavascript;


class CodeConverterState_TABSTRACT extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		if($this->stateMachine->currentScope instanceof GlobalScope){
			//Do nothing - abstract classes don't affect JS code generation.
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}

		if($this->stateMachine->currentScope instanceof ClassScope){
			//Abstract functions inside a class are commented out
			$this->changeToState(CONVERTER_STATE_ABSTRACT_FUNCTION);
			return TRUE;
		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



?>