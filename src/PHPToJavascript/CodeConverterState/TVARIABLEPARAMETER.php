<?php

namespace PHPToJavascript;


class CodeConverterState_TVARIABLEPARAMETER extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {

		//
		$variableName = cVar($value);

		//$this->stateMachine->currentScope->addParameterName($variableName);

//
		$this->stateMachine->addScopedVariable($variableName, $this->stateMachine->variableFlags);
		//$scopedVariableName = $this->stateMachine->getVariableNameForScope(CODE_SCOPE_FUNCTION_PARAMETERS, $variableName, FALSE);
		//$scopedVariableName = $variableName;
		$this->stateMachine->addJS($variableName);
		//$this->stateMachine->clearVariableFlags();
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}




?>