<?php

namespace PHPToJavascript;

/* Str_replace limited by count
*/
function str_replace_count($search, $replace, $subject, $count){
	for($x=0; $x<$count ; $x++){
		$pos = strpos($subject, $search);
		if ($pos !== FALSE) {
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		}
	}

	return $subject;
}



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

		if(($this->variableFlag & DECLARATION_TYPE_PRIVATE) == 0){
			$jsRaw = $this->getJS();

			if(($this->variableFlag & DECLARATION_TYPE_STATIC)){
				//$jsRaw = str_replace(PUBLIC_FUNCTION_MARKER_MAGIC_STRING, $parentScopeName.".", $jsRaw);
			}
			else{
				$jsRaw = str_replace(PUBLIC_FUNCTION_MARKER_MAGIC_STRING, "this.", $jsRaw);

				//Functions declared as prototypes on the class object need to have a semi-colon
				//to avoid a missing ';' warning in jsLint
				$jsRaw = trim($jsRaw).";\n\n";
				return $jsRaw;
			}
		}
		else{
			$result = "this.".$this->getName()." = ";
			$jsRaw = $this->getJS();
			$jsRaw = str_replace_count($this->getName(), '/*'.$this->getName().'*/', $jsRaw, 1);
			$result .= $jsRaw;
			return $result;
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
				return $jsRaw;
			}
			else{
//				$jsRaw = str_replace(PUBLIC_FUNCTION_MARKER_MAGIC_STRING, $parentScopeName.".prototype.", $jsRaw);
//
//				//Functions declared as prototypes on the class object need to have a semi-colon
//				//to avoid a missing ';' warning in jsLint
//				$jsRaw = trim($jsRaw).";\n\n";
			}
		}
		return "";
	}

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