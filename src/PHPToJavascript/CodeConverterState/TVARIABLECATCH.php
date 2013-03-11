<?php

namespace PHPToJavascript;


class CodeConverterState_TVARIABLECATCH extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {
		$variableName = cVar($value);
		$this->stateMachine->addScopedVariable($variableName, $this->stateMachine->variableFlags);
		$this->stateMachine->currentScope->addExceptionName($variableName);
		$this->stateMachine->addJS($variableName);
		$this->stateMachine->clearVariableFlags();
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}




?>