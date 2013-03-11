<?php

namespace PHPToJavascript;

class CodeConverterState_Comma extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		$this->stateMachine->addJS(',');

		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
			$this->stateMachine->currentScope->setBeforeVariable(true);
		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



?>