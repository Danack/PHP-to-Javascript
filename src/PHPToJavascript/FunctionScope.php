<?php

namespace PHPToJavascript;

class FunctionScope extends CodeScope{

	function getType(){
		return CODE_SCOPE_FUNCTION;
	}

	function	startOfFunction(){
		if($this->bracketCount == 1){//And we're past the first opening bracket
			return TRUE;
		}
		return FALSE;
	}

	function	getScopedVariableForScope($variableName, $isClassVariable){
		$cVar = cvar($variableName);

		if(array_key_exists($cVar, $this->scopedVariables) == TRUE){

			$variableFlag = $this->scopedVariables[$cVar];

			if($variableFlag & DECLARATION_TYPE_STATIC){
				return 	$this->name.".".$variableName;
			}
			else if($isClassVariable == TRUE){
				return 	'this.'.$variableName;
			}

			return $variableName;
		}

		return NULL;
	}
}




?>