<?php

namespace PHPToJavascript;


class CodeConverterState_TVARIABLEGLOBAL extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {
		$variableName = cVar($value);


		$this->stateMachine->addScopedVariable($variableName, 0);
		$this->stateMachine->addJS($variableName);

		$this->stateMachine->clearVariableFlags();

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}




?>