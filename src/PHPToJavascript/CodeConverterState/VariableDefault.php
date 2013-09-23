<?php

namespace PHPToJavascript;

class CodeConverterState_VariableDefault extends CodeConverterState{
	function	processToken($name, $value, $parsedToken){

		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
			$this->stateMachine->addJS( "/*". $value ."*/");
			$this->stateMachine->currentScope->setDefaultValueForPreviousVariable($value);
		}
		else{
			/*if (substr($value,0,1)=="'" || substr($value,0,1)=='"'){
				$value = str_replace(PHP_EOL,'\\'.PHP_EOL,$value);
			}*/
			$this->stateMachine->addJS($value);
		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


