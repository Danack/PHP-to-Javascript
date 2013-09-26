<?php

namespace PHPToJavascript;

class CodeConverterState_Equals extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
//		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
//			//Don't add an equals - default parameters are set inside the JS function
//
//            $this->changeToState(CONVERTER_STATE_CAPTURING_DEFAULT_VALUE);
//            
//		}
//		else{ 
			$this->stateMachine->addJS($name);
//		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


