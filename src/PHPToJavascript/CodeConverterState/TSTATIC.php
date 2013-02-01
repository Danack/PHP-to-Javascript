<?php

namespace PHPToJavascript;


class CodeConverterState_TSTATIC extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		if($this->stateMachine->variableFlags & DECLARATION_TYPE_NEW){
			$this->stateMachine->addJS($this->stateMachine->getClassName());
		}
		else{
			$this->stateMachine->variableFlags |= DECLARATION_TYPE_STATIC;
		}
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}






?>