<?php

namespace PHPToJavascript;

class CodeConverterState_ARRAY extends CodeConverterState {

//	Replace this with a generic array is starting function.

	function	processToken($name, $value, $parsedToken){
		if ($this->stateMachine->currentScope instanceof FunctionParameterScope){
			if($this->stateMachine->currentScope->beforeVariable == true){
				$this->stateMachine->addJS("/*".$value."*/");
				$this->changeToState(CONVERTER_STATE_DEFAULT);
				return;
			}
		}

		$classScope = false;

		$this->stateMachine->startArrayScope($value);

		//$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


?>