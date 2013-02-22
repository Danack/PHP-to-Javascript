<?php

namespace PHPToJavascript;

class CodeConverterState_Comma extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		$this->stateMachine->addJS(',');

//		if($this->stateMachine->currentScope instanceof ArrayScope){
//			$this->stateMachine->currentScope->closeEntry();
//		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



?>