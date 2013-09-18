<?php

namespace PHPToJavascript;
class CodeConverterState_Semicolon extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {
		if ($name==";"){
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}

