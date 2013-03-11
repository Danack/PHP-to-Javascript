<?php

namespace PHPToJavascript;

class CodeConverterState_TSTRING extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		$value = convertPHPValueToJSValue($value);

		$defineValue = $this->stateMachine->getDefine($value);

		if($defineValue !== FALSE){
			$this->stateMachine->addJS("'".$defineValue."'");
		}
		//TODO add isClass($value)
		else if(strcmp('static', $value) == 0 ||
			strcmp('self', $value) == 0){
			$this->stateMachine->addJS($this->stateMachine->getClassName());
		}
		else if($this->stateMachine->currentScope instanceof FunctionParameterScope){
			$this->stateMachine->addJS( "/*". $value ."*/");
			$this->stateMachine->currentScope->setDefaultValueForPreviousVariable($value);
		}
		else if($this->stateMachine->currentScope instanceof CatchScope){
			//$this->stateMachine->currentScope->addExceptionName($value);
			$this->stateMachine->addJS( "/*". $value ."*/");
		}
		else{
			$this->stateMachine->addJS($value);
		}

		//TODO - added this to fix "SomeClass::someFunc()" leaving variableFlags in non zero state
		//But not sure if this is safe.
		$this->stateMachine->variableFlags = 0;

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}






?>