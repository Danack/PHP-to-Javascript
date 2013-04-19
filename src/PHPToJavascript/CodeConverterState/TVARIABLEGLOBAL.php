<?php

namespace PHPToJavascript;


class CodeConverterState_TVARIABLEGLOBAL extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {

		if($value == "\$this"){
			//TODO - if this ever happens it is a bug.
			$this->stateMachine->addJS("this");
		}

		$variableName = cVar($value);

		if($variableName == 'lolWutTest'){
			echo "/*hmm*/";
		}

		$wasAdded = $this->stateMachine->addScopedVariable($variableName, $this->stateMachine->variableFlags);

		if($wasAdded == true){
			$this->stateMachine->addJS("var ");
		}

		$enclosedVariableName = $this->stateMachine->encloseVariable($variableName);

		$this->stateMachine->addJS($enclosedVariableName);

		$this->stateMachine->clearVariableFlags();

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}




?>