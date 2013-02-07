<?php

namespace PHPToJavascript;

class CodeConverterState_VariableValue extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

//		if($this->stateMachine->variableFlags & DECLARATION_TYPE_STATIC){
//			$this->stateMachine->currentScope->addStaticVariable($variableName);
//		}
//		else if($this->stateMachine->variableFlags & DECLARATION_TYPE_PUBLIC){
//
		if ($name == 'T_WHITESPACE' ||
			$name == '=' ||
			$name == 'T_CONSTANT_ENCAPSED_STRING' ||
			$name == 'T_LNUMBER' ||
			$name == 'T_COMMENT'){
			$this->stateMachine->currentScope->addToVariableValue($value);
			return;
		}

		if($name == ';'){
			$this->stateMachine->clearVariableFlags();
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return;
		}

		xdebug_break();
		throw new \Exception("The only symbols expected here are '=', ';' and some sort of value. Isntead received token name [$name] with value [$value].");
	}
}



?>