<?php

define('CODE_SCOPE_GLOBAL', 'CODE_SCOPE_GLOBAL');
define('CODE_SCOPE_FUNCTION', 'CODE_SCOPE_FUNCTION');
define('CODE_SCOPE_FUNCTION_PARAMETERS', 'CODE_SCOPE_FUNCTION_PARAMETERS');
define('CODE_SCOPE_CLASS', 'CODE_SCOPE_CLASS');




class CodeScope{

	var $bracketCount = 0;
	var $type;

	var $name;

	var $defaultValues = array();

	/** @var CodeScope */
	var $parentScope;

	/**
	 * @var string[]
	 */
	public $scopedVariables = array();

	function getName(){
		return $this->name;
	}


	function __construct($type, $name, $parentScope){
		$this->type = $type;
		$this->name = $name;
		$this->parentScope = $parentScope;
	}

	function	pushBracket(){
		$this->bracketCount += 1;
	}

	function	popBracket(){
		$this->bracketCount -= 1;

		if($this->bracketCount == 0){
			return TRUE;
		}

		return FALSE;
	}

	function	addScopedVariable($variableName, $variableFlag){
		$cVar = cvar($variableName);

		if(array_key_exists($cVar, $this->scopedVariables) == FALSE){
			$this->scopedVariables[$cVar] = $variableFlag;// $this->name.".".$variableName;
		}
	}

	function	setDefaultValueForPreviousVariable($value){
		$allKeys = array_keys($this->scopedVariables);
		$variableName = $allKeys[count($allKeys) - 1];

		$this->defaultValues[$variableName] = $value;
	}

	function	getVariablesWithDefaultParameters(){
		return $this->defaultValues;
	}


	function	getVariablesWithDefault(){
		return $this->defaultValues;
	}

	function	getScopedVariable($variableName, $isClassVariable){

		$cVar = cvar($variableName);

		if($cVar == 'testFunction'){
			echo "scopeType = ".$this->type." isClassVariable ".$isClassVariable ."\n";
		}

		if(array_key_exists($cVar, $this->scopedVariables) == TRUE){

			$variableFlag = $this->scopedVariables[$cVar];

			if($isClassVariable == TRUE && $this->type == CODE_SCOPE_CLASS){
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
			else{
				if($variableFlag & DECLARATION_TYPE_STATIC){
					return 	$this->name.".".$variableName;
				}
				else if($isClassVariable == TRUE){
					return 	'this.'.$variableName;
				}

				return $variableName;
			}
		}


		if($this->parentScope != NULL){
			return $this->parentScope->getScopedVariable($variableName, $isClassVariable);
		}

		if($isClassVariable == TRUE){
			//Either a function or property set below where it is defined.
			return 	'this.'.$variableName;
		}

		return $variableName;
	}


	function	startOfFunction(){
		if($this->type == CODE_SCOPE_FUNCTION){//If we're in a function
			if($this->bracketCount == 1){//And we're past the first opening bracket
				return TRUE;
			}
		}
		return FALSE;
	}

}

?>