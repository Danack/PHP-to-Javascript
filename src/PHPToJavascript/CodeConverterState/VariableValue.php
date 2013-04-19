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

		if ($name == "T_ARRAY") {
			$this->changeToState(CONVERTER_STATE_ARRAY);
			return true;//reprocess token
		}

		if ($name == "[") {

			$classScope = false;
			$this->stateMachine->startArrayScope("");
//			if ($this->stateMachine->currentScope instanceof ClassScope) {
//				$classScope = $this->stateMachine->currentScope;
//			}
//
//
//			$this->stateMachine->pushScope(CODE_SCOPE_ARRAY, $value, DECLARATION_TYPE_SQUARE_ARRAY);
//
//			if ($classScope != false) {
//				$this->stateMachine->currentScope->setVariableName($classScope->currentVariableName);
//			}
//
//			$this->changeToState(CONVERTER_STATE_DEFAULT);
			$this->stateMachine->currentTokenStream->insertToken('(');
			return false;
		}

//		if($this->stateMachine->currentScope instanceof ClassScope){
//			throw new \Exception("Sorry, initializing class variables to an array is not supported yet. The difficultly is that the initial value must be moved from where it is declared to outside the class declaration code, which is difficult for arrays. Please instead declare the variable as null, and then assign it an array in the constructor.");
//		}

		throw new \Exception("The only symbols expected here are '=', ';' and some sort of value. Instead received token name [$name] with value [$value].");
	}
}



?>