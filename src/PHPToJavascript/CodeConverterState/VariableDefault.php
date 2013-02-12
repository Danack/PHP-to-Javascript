<?php

namespace PHPToJavascript;

class CodeConverterState_VariableDefault extends CodeConverterState{
	function	processToken($name, $value, $parsedToken){

		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
			$this->stateMachine->addJS( "/*". $value ."*/");
			$this->stateMachine->currentScope->setDefaultValueForPreviousVariable($value);
		}
		else{
			$this->stateMachine->addJS($value);
		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


?>