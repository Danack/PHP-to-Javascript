<?php

namespace PHPToJavascript;

class CodeConverterState_ARRAY extends CodeConverterState {

	function	processToken($name, $value, $parsedToken){
		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
			if($this->stateMachine->currentScope->beforeVariable == true){
				$this->stateMachine->addJS("/*".$value."*/");
				$this->changeToState(CONVERTER_STATE_DEFAULT);
				return;
			}
		}

		$this->stateMachine->pushScope(CODE_SCOPE_ARRAY, $value);
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


?>