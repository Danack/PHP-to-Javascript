<?php

namespace PHPToJavascript;


class ArrayScope extends CodeScope{

	var $parensCount = 0;

	/**
	 * @param $variableName
	 * @param $isClassVariable - whether the variable was prefixed by $this
	 * @return mixed
	 *
	 * For a given variable name, try to find the variable in the current scope.
	 */
	function    getScopedVariableForScope($variableName, $isClassVariable) {
		//Array scopes don't contain variables.
		return NULL;
	}

	function	pushParens(){
		//does nothing
	}

	function	popParens(){
		//Does nothings
		return FALSE;
	}

	function getType(){
		return CODE_SCOPE_ARRAY;
	}
}


?>