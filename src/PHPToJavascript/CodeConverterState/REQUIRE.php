<?php

namespace PHPToJavascript;

class CodeConverterState_REQUIRE extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->addJS('//'.$value);
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}




?>