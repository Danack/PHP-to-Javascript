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
			$this->stateMachine->addJS("this");
			$this->isClassVariable = TRUE;
			return;
		}

		$variableName = cVar($value);

		if($this->stateMachine->variableFlags & DECLARATION_TYPE_STATIC){
			$scopeName = $this->stateMachine->getScopeName();
			$this->stateMachine->addJS("if (typeof ".$scopeName.".$variableName == 'undefined')\n ");
		}

		if ($this->isClassVariable == TRUE && (
				$name == ")" || $name == ',' || $name == ';'
			)
		) {
			//keyword 'this' has been passed as a variable e.g.
			//json_encode_object(this)
			//$this->stateMachine->addJS("this)");
			$this->stateMachine->addJS($name);
		}
		else if($name == "T_OBJECT_OPERATOR"){
			$this->stateMachine->addJS(".");
		}
		else if($name == "T_STRING" ||
				$name == "T_VARIABLE") {

			$scopedVariableName = $this->stateMachine->getVariableNameForScope($variableName, $this->isClassVariable, $this->stateMachine->variableFlags);

			//$this->stateMachine->addJS($scopedVariableName);

			$enclosedVariableName = $this->stateMachine->encloseVariable($scopedVariableName);
			$this->stateMachine->addJS($enclosedVariableName);

		}
		else{
			throw new \Exception("Unexpected token in state CodeConverterState_TVARIABLEFUNCTION, wasn't expected an [".$name."]");
		}

		$this->isClassVariable = FALSE;
		$this->stateMachine->clearVariableFlags();
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}




?>