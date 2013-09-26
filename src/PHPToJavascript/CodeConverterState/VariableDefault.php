<?php

namespace PHPToJavascript;

//TODO - I think this is not used, or at least it's weird that there's a whole PHP token
//T_CONSTANT_ENCAPSED_STRING dedicated to the default values for parameters to functions.
class CodeConverterState_VariableDefault extends CodeConverterState{
	function	processToken($name, $value, $parsedToken){

		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
			//$this->stateMachine->addJS( "/*". $value ."*/");
			$this->stateMachine->currentScope->addToJsForPreviousVariable($value);
		}
		else{
			$this->stateMachine->addJS($value);
		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


