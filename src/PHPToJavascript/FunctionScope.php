<?php

namespace PHPToJavascript;
class FunctionScope extends CodeScope {

	function getType() {
		return CODE_SCOPE_FUNCTION;
	}

	function    startOfFunction() {
		if ($this->bracketCount == 1) { //And we're past the first opening bracket
			return true;
		}
		return false;
	}

	function    getScopedName() {
		$containingClassScope = $this->findAncestorScopeByType(CODE_SCOPE_CLASS);
		if ($containingClassScope == null) {
			return $this->name;
		} else {
			return "this." . $this->name;
		}
	}


	function    getScopedVariableForScope($variableName, $isClassVariable) {
		$cVar = cvar($variableName);
		if (array_key_exists($cVar, $this->scopedVariables) == true) {
			$variableFlag = $this->scopedVariables[$cVar];
			if ($variableFlag & DECLARATION_TYPE_STATIC) {
				return $this->getScopedName() . "." . $variableName;
			} else if ($isClassVariable == true) {
				if (strpos($variableName, "$") !== false) {
					//it's a variable variable like "this->$var";
					return 'this[' . $variableName . ']';
				} else {
					return 'this.' . $variableName;
				}
			}
			return $variableName;
		}
		return null;
	}
}


