<?php

namespace PHPToJavascript;




class CodeConverterState_FUNCTION extends CodeConverterState {

	function	processToken($name, $value, $parsedToken){

		if($name == "T_STRING"){

			$previousScope = $this->stateMachine->currentScope;

			$this->stateMachine->pushScope(CODE_SCOPE_FUNCTION_PARAMETERS, $value, $this->stateMachine->variableFlags);

			if($previousScope instanceof ClassScope){
				//$this->stateMachine->markMethodsStart();
				//echo "Gaah";
				$previousScope->markMethodsStart();

				if($this->stateMachine->variableFlags & DECLARATION_TYPE_PRIVATE){
					$this->stateMachine->addJS("function $value ");
				}
				else{
					$this->stateMachine->addJS(PUBLIC_FUNCTION_MARKER_MAGIC_STRING."$value = function ");
				}

				//$this->stateMachine->addJS("this.$value = function");
			}
			else{
				//if($this->stateMachine->variableFlags & DECLARATION_TYPE_PRIVATE){
				$this->stateMachine->addJS("function $value ");
//				}
//				else{
//					$this->stateMachine->addJS(PUBLIC_FUNCTION_MARKER_MAGIC_STRING."$value = function ");
//				}
			}

			/*
			if($value == "__construct"){
				$this->stateMachine->markConstructorStart();
			}
			*/

			$this->stateMachine->clearVariableFlags();
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}





?>