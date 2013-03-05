<?php

namespace PHPToJavascript;

class CodeConverterState_TUSE extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		if($this->stateMachine->currentScope instanceof GlobalScope){
			$this->changeToState(CONVERTER_STATE_IMPORT_NAMESPACE);
		}
		else if($this->stateMachine->currentScope instanceof ClassScope){
			$this->changeToState(CONVERTER_STATE_T_EXTENDS);
		}
		else{
			throw new \Exception("use is only expected in Global and class scopes. Don't know what to do with it here.");
		}

		return true;
	}
}





?>