<?php

namespace PHPToJavascript;



class CodeConverterState_TVARIABLECLASS extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {

		$variableName = cVar($value);

		if($this->stateMachine->variableFlags & DECLARATION_TYPE_PRIVATE){
			$this->stateMachine->addJS("var ");
		}
		else if($this->stateMachine->variableFlags & DECLARATION_TYPE_STATIC){
			//$this->stateMachine->addJS($this->stateMachine->currentScope->getName().".");
			$this->stateMachine->addJS("var ");
		}
		else if($this->stateMachine->variableFlags & DECLARATION_TYPE_PUBLIC){
			$this->stateMachine->addJS("this.");
			//$this->stateMachine->addJS($this->stateMachine->currentScope->getName().".");
		}

		$this->stateMachine->addScopedVariable($variableName, $this->stateMachine->variableFlags);
		$this->stateMachine->addJS($variableName);

		$this->stateMachine->clearVariableFlags();
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



?>