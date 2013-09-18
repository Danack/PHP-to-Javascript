<?php

namespace PHPToJavascript;
class CodeConverterState_TVARIABLECLASS extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {
		$variableName = cVar($value);
		if ($value == "\$this") {
			$this->stateMachine->addJS("this");
		}
		$this->stateMachine->addScopedVariable($variableName, $this->stateMachine->variableFlags);
		if ($this->stateMachine->variableFlags & DECLARATION_TYPE_STATIC) {
			$this->stateMachine->currentScope->addStaticVariable($variableName);
			$this->changeToState(CONVERTER_STATE_VARIABLE_VALUE);
		} else if (false /*&& $this->stateMachine->variableFlags & DECLARATION_TYPE_PUBLIC*/) {
			/* addPublicVariable is deprecated*/
			$this->stateMachine->currentScope->addPublicVariable($variableName);
			$this->changeToState(CONVERTER_STATE_VARIABLE_VALUE);
		} else {
			if ($this->stateMachine->variableFlags & DECLARATION_TYPE_PUBLIC){
				$this->stateMachine->addJS("this.");
			}else{
				$this->stateMachine->addJS("var ");
			}
			$this->stateMachine->addJS($variableName);
			$this->stateMachine->clearVariableFlags();
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}


