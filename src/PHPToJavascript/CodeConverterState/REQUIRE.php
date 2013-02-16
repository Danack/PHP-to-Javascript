<?php

namespace PHPToJavascript;

class CodeConverterState_REQUIRE extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		if($name == 'T_CONSTANT_ENCAPSED_STRING'){
			$this->stateMachine->addJS('// Opening the require'.$value);

			$this->changeToState(CONVERTER_STATE_DEFAULT);
			$this->stateMachine->requireFile($value);
		}

	}
}




?>