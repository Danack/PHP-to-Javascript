<?php

namespace PHPToJavascript;
class GlobalScope extends CodeScope {

	function getType() {
		return CODE_SCOPE_GLOBAL;
	}

	function    getScopedVariableForScope($variableName, $isClassVariable) {
		if ($isClassVariable == true) {
			return null; //Class variables would not use a global variable
		}
		$cVar = cvar($variableName);
		if (array_key_exists($cVar, $this->scopedVariables) == true) {
			return $variableName;
		}
		return null;
	}
}




