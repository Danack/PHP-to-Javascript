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

	//Whether we are currently before or after a variable name
	var $beforeVariable = TRUE;

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
			//$result = "this.".$this->getName()." = ";
			$result = $this->getJS();
			//$jsRaw = str_replace_count($this->getName(), '/*'.$this->getName().'*/', $jsRaw, 1);
			//$result .= $jsRaw;
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

	function	setDefaultValueForPreviousVariable($value){

		if($this->beforeVariable == TRUE){
			//It's actually a type-hint not a default value, as it before the variable name
			return;
		}

		$allKeys = array_keys($this->scopedVariables);
		if(count($allKeys) == 0){
			throw new \Exception("Trying to add default variable but not variables found yet.");
		}

		$variableName = $allKeys[count($allKeys) - 1];

		$this->defaultValues[$variableName] = convertPHPValueToJSValue($value);
	}

	/**
	 * @param $variableName
	 * @param $variableFlag
	 * @return bool
	 */
	function	addScopedVariable($variableName, $variableFlag){
		$result = parent::addScopedVariable($variableName, $variableFlag);
		$this->setBeforeVariable(FALSE);
		return $result;
	}

	function setBeforeVariable($boolean){
		$this->beforeVariable = $boolean;
	}
}




?>