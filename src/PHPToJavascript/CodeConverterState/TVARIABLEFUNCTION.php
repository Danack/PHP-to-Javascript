<?php

namespace PHPToJavascript;


class CodeConverterState_TVARIABLEFUNCTION extends CodeConverterState {

	var $isClassVariable;

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->isClassVariable = FALSE;
	}

	function    processToken($name, $value, $parsedToken) {

		if($value == '$this'){
			$this->isClassVariable = TRUE;
			return;
		}

		if($name == 'T_OBJECT_OPERATOR'){
			//This is skipped as private class variables are converted from
			// "$this->varName" to "varName" - for the joy of Javascript scoping.
			return;
		}

//

		$variableName = cVar($value);

//		if($this->stateMachine->currentScope == CODE_SCOPE_FUNCTION_PARAMETERS){
//			$this->stateMachine->currentScope->addParameterName($variableName);
//		}

		if($this->stateMachine->variableFlags & DECLARATION_TYPE_STATIC){
			$scopeName = $this->stateMachine->getScopeName();
			$this->stateMachine->addJS("if (typeof ".$scopeName.".$variableName == 'undefined')\n ");
		}

		if($this->isClassVariable == FALSE){ //Don't add class variables to the function scope
			$this->stateMachine->addScopedVariable($variableName, $this->stateMachine->variableFlags);
		}

		$scopedVariableName = $this->stateMachine->getVariableNameForScope(/*CODE_SCOPE_FUNCTION,*/ $variableName, $this->isClassVariable);

//		if($this->isClassVariable == TRUE){
//			$scopedVariableName = $this->stateMachine->getVariableNameForScope(/*CODE_SCOPE_CLASS,*/ $variableName, $this->isClassVariable);
//		}
//		else{
//			$scopedVariableName = $this->stateMachine->getVariableNameForScope(/*CODE_SCOPE_FUNCTION,*/ $variableName, $this->isClassVariable);
//		}

		$this->stateMachine->addJS($scopedVariableName);

		$this->isClassVariable = FALSE;
		$this->stateMachine->clearVariableFlags();
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}




?>