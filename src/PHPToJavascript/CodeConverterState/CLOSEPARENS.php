<?php

namespace PHPToJavascript;

class CodeConverterState_CLOSEPARENS extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		if($this->stateMachine->currentScope instanceof FunctionParameterScope ||
			$this->stateMachine->currentScope instanceof CatchScope){
			$this->stateMachine->pushScope(
				CODE_SCOPE_FUNCTION,
				$this->stateMachine->currentScope->getName()
			);
		}

		$this->stateMachine->addJS(')');

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



?>