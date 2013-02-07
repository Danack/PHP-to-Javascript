<?php

namespace PHPToJavascript;


class CodeConverterState_TVARIABLEPARAMETER extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {
		$variableName = cVar($value);

		$this->stateMachine->addScopedVariable($variableName, $this->stateMachine->variableFlags);
		$this->stateMachine->addJS($variableName);
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}




?>