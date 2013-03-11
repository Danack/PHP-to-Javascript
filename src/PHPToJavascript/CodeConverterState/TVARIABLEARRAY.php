<?php

namespace PHPToJavascript;


class CodeConverterState_TVARIABLEARRAY extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {
		$variableName = cVar($value);
		$this->stateMachine->addJS($variableName);
		$this->stateMachine->clearVariableFlags();
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}




?>