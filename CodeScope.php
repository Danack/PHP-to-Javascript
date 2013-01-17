<?php

define('CODE_SCOPE_GLOBAL', 'CODE_SCOPE_GLOBAL');
define('CODE_SCOPE_FUNCTION', 'CODE_SCOPE_FUNCTION');
define('CODE_SCOPE_CLASS', 'CODE_SCOPE_CLASS');




class CodeScope{

	var $bracketCount = 0;
	var $type;

	var $name;

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

	function	getScopedVariable($variableName, $isClassVariable){

		$cVar = cvar($variableName);

		if(array_key_exists($cVar, $this->scopedVariables) == TRUE){

			$variableFlag = $this->scopedVariables[$cVar];

			if($isClassVariable == TRUE && $this->type == CODE_SCOPE_CLASS){
				if($variableFlag & DECLARATION_TYPE_PRIVATE){
					return 	$variableName;
				}
				if($variableFlag & DECLARATION_TYPE_PUBLIC){
					return 	'this.'.$variableName;
				}
				if($variableFlag & DECLARATION_TYPE_STATIC){
					return 	$this->name.".".$variableName;
				}
			}
			else{
				if($variableFlag & DECLARATION_TYPE_STATIC){
					return 	$this->name.".".$variableName;
				}

				return $variableName;
			}
		}


		if($this->parentScope != NULL){
			return $this->parentScope->getScopedVariable($variableName, $isClassVariable);
		}


		return $variableName;
	}
}

//class_static
//class private
//class_standard
//
//function static
//function_standard


?>