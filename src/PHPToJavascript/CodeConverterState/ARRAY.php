<?php

namespace PHPToJavascript;

class CodeConverterState_ARRAY extends CodeConverterState {

//	public function		enterState($extraParams = array()){
//		if (array_key_exists('classVariable', $extraParams) == true) {
//			echo "ACTIVATE";
//		}
//	}


	function	processToken($name, $value, $parsedToken){
		if ($this->stateMachine->currentScope instanceof FunctionParameterScope){
			if($this->stateMachine->currentScope->beforeVariable == true){
				$this->stateMachine->addJS("/*".$value."*/");
				$this->changeToState(CONVERTER_STATE_DEFAULT);
				return;
			}
		}

		$classScope = false;

		if ($this->stateMachine->currentScope instanceof ClassScope) {
			$classScope = $this->stateMachine->currentScope;
		}

		$this->stateMachine->pushScope(CODE_SCOPE_ARRAY, $value);
		$this->changeToState(CONVERTER_STATE_DEFAULT);

		if ($classScope != false) {
			$this->stateMachine->currentScope->setVariableName($classScope->currentVariableName);
		}

	}
}


?>