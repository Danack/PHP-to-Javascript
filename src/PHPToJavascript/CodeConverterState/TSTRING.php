<?php

namespace PHPToJavascript;

class CodeConverterState_TSTRING extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		$value = convertPHPValueToJSValue($value);

		$defineValue = $this->stateMachine->getDefine($value);

		if($defineValue !== FALSE){
			$this->stateMachine->addJS("'".$defineValue."'");
		}
		else if(strcmp('static', $value) == 0 ||
			strcmp('self', $value) == 0){
			$this->stateMachine->addJS($this->stateMachine->getClassName());
		}
		else if($this->stateMachine->currentScope instanceof FunctionParameterScope){
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