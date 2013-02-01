<?php

namespace PHPToJavascript;

class CodeConverterState_Equals extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
			//Don't add an equals - default parameters are set inside the JS function
		}
		else{
			$this->stateMachine->addJS($name);
		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}





?>