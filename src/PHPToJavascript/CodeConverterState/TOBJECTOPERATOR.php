<?php

namespace PHPToJavascript;
class CodeConverterState_TOBJECTOPERATOR extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {
		if ($name == 'T_VARIABLE') {
			if (strpos($value, '$') !== false) {
				$this->stateMachine->addJS("[");
				$this->stateMachine->addSymbolAfterNextToken(']');
			} else {
				$this->stateMachine->addJS(".");
			}
			$this->changeToState(CONVERTER_STATE_VARIABLE);
			return true;
		}
		if ($name == "T_STRING") {
			$this->stateMachine->addJS(".");
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return true;
		}
		//echo "Interesting - name $name value = $value\n";
	}
}

