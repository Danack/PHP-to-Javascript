<?php

define('CODE_SCOPE_GLOBAL', 'CODE_SCOPE_GLOBAL');
define('CODE_SCOPE_FUNCTION', 'CODE_SCOPE_FUNCTION');
define('CODE_SCOPE_FUNCTION_PARAMETERS', 'CODE_SCOPE_FUNCTION_PARAMETERS');
define('CODE_SCOPE_CLASS', 'CODE_SCOPE_CLASS');


function	convertPHPValueToJSValue($value){

	if($value == 'FALSE'){
		return 'false';
	}

	if($value == 'TRUE'){
		return 'true';
	}

	return $value;
}


abstract class CodeScope{

	use SafeAccess;

	var $bracketCount = 0;

	var $name;

	var $defaultValues = array();

	/** @var CodeScope */
	var $parentScope;

	/**
	 * @var string[]
	 */
	public $scopedVariables = array();

	/**
	 * @param $variableName
	 * @param $isClassVariable - whether the variable was prefixed by $this
	 * @return mixed
	 *
	 * For a given variable name, try to find the variable in the current scope.
	 *
	 * //TODO - change $isClassVaraible to be a flag to support FLAG_THIS, FLAG_SELF, FLAG_STATIC, FLAG_PARENT
	 */
	abstract	function	getScopedVariableForScope($variableName, $isClassVariable);
	abstract	function getType();

	function	getScopedVariable($variableName, $isClassVariable){
		$result = $this->getScopedVariableForScope($variableName, $isClassVariable);

		if($result == NULL){
			if($this->parentScope != NULL){
				return $this->parentScope->getScopedVariable($variableName, $isClassVariable);
			}
		}

		return $result;
	}

	function getName(){
		return $this->name;
	}

	function __construct($name, $parentScope){
		$this->name = $name;
		$this->parentScope = $parentScope;
	}

	function	pushBracket(){
		$this->bracketCount += 1;

		xdebug_break();
		echo "bracket count = ".$this->bracketCount."\n";
	}

	function	popBracket(){
		$this->bracketCount -= 1;

		echo "bracket count = ".$this->bracketCount."\n";

		if($this->bracketCount <= 0){
			return TRUE;
		}

		return FALSE;
	}

	function	addScopedVariable($variableName, $variableFlag){
		$cVar = cvar($variableName);

		if(PHPToJavascript_TRACE == TRUE){
			echo "Added variable $variableName to scope ".get_class($this)."\n";
		}

		if(array_key_exists($cVar, $this->scopedVariables) == FALSE){
			$this->scopedVariables[$cVar] = $variableFlag;// $this->name.".".$variableName;
		}
	}

	function	setDefaultValueForPreviousVariable($value){

		$allKeys = array_keys($this->scopedVariables);
		if(count($allKeys) == 0){
			throw new Exception("Trying to add default variable but not variables found yet.");
		}

		$variableName = $allKeys[count($allKeys) - 1];

		$this->defaultValues[$variableName] = convertPHPValueToJSValue($value);
	}

	function	getVariablesWithDefaultParameters(){
		return $this->defaultValues;
	}

//	function	getVariablesWithDefault(){
//		return $this->defaultValues;
//	}

	function	startOfFunction(){
		return FALSE;
	}

}


class GlobalScope extends CodeScope{

	function getType(){
		return CODE_SCOPE_GLOBAL;
	}

	function	getScopedVariableForScope($variableName, $isClassVariable){
		if($isClassVariable == TRUE){
			return NULL;	//Class variables would not use a global variable
		}

		$cVar = cvar($variableName);
		if(array_key_exists($cVar, $this->scopedVariables) == TRUE){
			return $variableName;
		}

		return NULL;
	}
}

class ClassScope extends CodeScope{

	function getType(){
		return CODE_SCOPE_CLASS;
	}

	function	getScopedVariableForScope($variableName, $isClassVariable){

		$cVar = cvar($variableName);

		if(array_key_exists($cVar, $this->scopedVariables) == TRUE){
			$variableFlag = $this->scopedVariables[$cVar];

			if($isClassVariable == TRUE){
				if($variableFlag & DECLARATION_TYPE_PRIVATE){
					return 	$variableName;
				}
				if($variableFlag & DECLARATION_TYPE_STATIC){
					return 	$variableName;
				}
				if($variableFlag & DECLARATION_TYPE_PUBLIC){
					return 	'this.'.$variableName;
				}
			}
		}

		if($isClassVariable == TRUE){
			//Either a function or property set below where it is defined.
 			// OR it could be a variable that is defined in the parent class' scope.
			return 	'this.'.$variableName;
		}

		return NULL;
	}
}

class FunctionParameterScope extends CodeScope{

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