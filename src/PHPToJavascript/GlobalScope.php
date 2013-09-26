<?php

namespace PHPToJavascript;


class GlobalScope extends CodeScope{

	function getType(){
		return CODE_SCOPE_GLOBAL;
	}

	function	getScopedVariableForScope($variableName, $variableFlags){
		if($variableFlags & DECLARATION_TYPE_CLASS){
			return NULL;	//Class variables would not use a global variable
		}

		$cVar = cvar($variableName);
		if(array_key_exists($cVar, $this->scopedVariables) == TRUE){
			return $variableName;
		}

		return NULL;
	}
}




