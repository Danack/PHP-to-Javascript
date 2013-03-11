<?php

namespace PHPToJavascript;




class CodeConverterState_FUNCTION extends CodeConverterState {

	function	processToken($name, $value, $parsedToken){

		if($name == "T_STRING"){

			$previousScope = $this->stateMachine->currentScope;

			$this->stateMachine->pushScope(CODE_SCOPE_FUNCTION_PARAMETERS, $value, $this->stateMachine->variableFlags);

			if($previousScope instanceof ClassScope){
				$previousScope->markMethodsStart();

				if($this->stateMachine->variableFlags & DECLARATION_TYPE_PRIVATE){
					$this->stateMachine->addJS("function $value ");
				}
				else{
					$this->stateMachine->addJS(PUBLIC_FUNCTION_MARKER_MAGIC_STRING."$value = function ");
				}
			}
			else{
				$this->stateMachine->addJS("function $value ");
			}

			$this->stateMachine->clearVariableFlags();
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}





?>