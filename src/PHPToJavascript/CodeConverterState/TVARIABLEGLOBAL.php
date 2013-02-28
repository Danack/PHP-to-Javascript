<?php

namespace PHPToJavascript;


class CodeConverterState_TVARIABLEGLOBAL extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {

		if($value == "\$this"){
			//TODO - if this ever happens it is a bug.
			$this->stateMachine->addJS("this");
		}

		$variableName = cVar($value);

		$this->stateMachine->addScopedVariable($variableName, 0);
		$this->stateMachine->addJS($variableName);

		$this->stateMachine->clearVariableFlags();

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}




?>