<?php

namespace PHPToJavascript;

class CodeConverterState_DoubleArrow extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

//		if($this->stateMachine->currentScope instanceof ArrayScope){
//			$this->stateMachine->currentScope->markKeyValueSeparator();
//		}
//		else{
//			//Is a doubleArrow possible anywhere else in PHP code?
//		}

		$this->stateMachine->addJS(':');

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



?>