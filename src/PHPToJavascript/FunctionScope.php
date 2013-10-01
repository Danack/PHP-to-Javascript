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

	function	getScopedName(){
		$containingClassScope = $this->findAncestorScopeByType(CODE_SCOPE_CLASS);

		if($containingClassScope == null){
			return $this->name;
		}
		else{
			return "this.".$this->name;
		}
	}


	function	getScopedVariableForScope($variableName, $variableFlags){
		$cVar = cvar($variableName);

		if(array_key_exists($cVar, $this->scopedVariables) == TRUE){
			//$variableFlag = $this->scopedVariables[$cVar];
            $variable = $this->scopedVariables[$cVar];
			//if($variableFlag & DECLARATION_TYPE_STATIC){
            if ($variable->isStatic() == true) {
				return 	$this->getScopedName().".".$variableName;
			}
			else if($variableFlags & DECLARATION_TYPE_CLASS){
				if(strpos($variableName, "$") !== FALSE){
					//it's a variable variable like "this->$var";
					return 	'this['.$variableName.']';
				}
				else{
					return 	'this.'.$variableName;
				}
			}

			return $variableName;
		}

		return NULL;
	}
}


