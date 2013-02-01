<?php

namespace PHPToJavascript;



class CodeConverterState_TPUBLIC extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->variableFlags |= DECLARATION_TYPE_PUBLIC;
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



?>