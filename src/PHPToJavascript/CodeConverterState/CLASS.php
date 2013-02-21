<?php

namespace PHPToJavascript;

class CodeConverterState_CLASS extends CodeConverterState {

	function	processToken($name, $value, $parsedToken){
		if($name == "T_STRING"){
			$this->stateMachine->pushScope(CODE_SCOPE_CLASS, $value);
			$this->stateMachine->addJS("function $value(".CONSTRUCTOR_PARAMETERS_POSITION.")");
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}




?>