<?php

namespace PHPToJavascript;

class CodeConverterState_VariableValue extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		if ($name == 'T_WHITESPACE' ||
			$name == '=' ||
			$name == 'T_CONSTANT_ENCAPSED_STRING' ||
			$name == 'T_LNUMBER' ||
			$name == 'T_COMMENT' ||
			$name == 'T_STRING'){

			$value = convertPHPValueToJSValue($value);

			$this->stateMachine->currentScope->addToVariableValue($value);
			return;
		}

		if($name == ';'){
			$this->stateMachine->clearVariableFlags();
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return;
		}

		if($this->stateMachine->currentScope instanceof ClassScope){
			throw new \Exception("Sorry, initializing class variables to an array is not supported yet. The difficultly is that the initial value must be moved from where it is declared to outside the class declaration code, which is difficult for arrays. Please instead declare the variable as null, and then assign it an array in the constructor.");
		}

		throw new \Exception("The only symbols expected here are '=', ';' and some sort of value. Instead received token name [$name] with value [$value].");
	}
}



?>