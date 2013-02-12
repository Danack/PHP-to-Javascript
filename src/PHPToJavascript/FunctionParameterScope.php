<?php

namespace PHPToJavascript;

class FunctionParameterScope extends CodeScope{

	var $variableFlag = 0;

	function	__construct($name, $parentScope, $variableFlag){
		parent::__construct($name, $parentScope);
		$this->variableFlag = $variableFlag;
	}

	function	getInPlaceJS(){
		if($this->getName() == "__construct"){
			//constructor gets included in ClassScope
			return "";
		}

		if(($this->variableFlag & DECLARATION_TYPE_PRIVATE) != 0){
			//return $this->getJSRaw();
			return $this->getJS();
		}
	}

	function	getDelayedJS($parentScopeName){

		if($this->getName() == "__construct"){
			//constructor gets included in ClassScope
			return "";
		}

		if(($this->variableFlag & DECLARATION_TYPE_PRIVATE) == 0){
			$jsRaw = $this->getJS();

			if(($this->variableFlag & DECLARATION_TYPE_STATIC)){
				$jsRaw = str_replace(PUBLIC_FUNCTION_MARKER_MAGIC_STRING, $parentScopeName.".", $jsRaw);
			}
			else{
				$jsRaw = str_replace(PUBLIC_FUNCTION_MARKER_MAGIC_STRING, $parentScopeName.".prototype.", $jsRaw);

				//Functions declared as prototypes on the class object need to have a semi-colon
				//to avoid a missing ';' warning in jsLint
				$jsRaw = trim($jsRaw).";\n\n";
			}
			return $jsRaw;
		}
	}

	/*
	function	getJS(){

		if($this->getName() == "__construct"){
			//constructor gets included in ClassScope
			return "";
		}

		if(($this->variableFlag & DECLARATION_TYPE_PRIVATE) != 0){
			return $this->getJSRaw();
		}
	} */

	function getType(){
		return CODE_SCOPE_FUNCTION_PARAMETERS;
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