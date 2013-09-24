<?php

namespace PHPToJavascript;

class CodeConverterState_TGLOBAL extends CodeConverterState {

    function    processToken($name, $value, $parsedToken) {
		if ($name == "T_VARIABLE") {
			$this->stateMachine->currentScope->scopedVariables[cVar($value)]=false;
		}
		if ($name == ";") {
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
    }
}