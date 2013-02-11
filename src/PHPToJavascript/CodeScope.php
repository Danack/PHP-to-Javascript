<?php

namespace PHPToJavascript;


abstract class CodeScope{

	use SafeAccess;

	var $bracketCount = 0;

	var $name;

	var $defaultValues = array();

	/** @var CodeScope */
	var $parentScope;

	//var $childScopes = array();

	var $jsElements = array();

	function addChild($scope){
		$this->jsElements[] = $scope;
	}

	function	addJS($jsString){
		$this->jsElements[] = $jsString;
	}

	function	getJS(){
		return $this->getJSRaw();
	}

	function	markMethodsStart(){
		throw new \Exception("This should only be called on ClassScope");
	}

	/**
	 * Get the JS code that needs to be moved to after the end of this scope
	 * @return string
	 */
	function  getEndOfScopeJS(){
		return "";
	}

	function	getJSRaw(){
		$js = "";
		$js .= $this->getJS_InPlace();
		$js .= "\n";
		$js .= $this->getEndOfScopeJS();
		$js .= "\n";
		$js .= $this->getChildDelayedJS();

		return $js;
	}


	function getJS_InPlace(){

		$js = "";

		foreach($this->jsElements as $jsElement){
			if($jsElement instanceof CodeScope){
				$js .= $jsElement->getJS();
			}
			else if(is_string($jsElement)){
				$js .= $jsElement;
			}
			else{
				throw new \Exception("Unknown type in this->jsElements of type [".get_class($jsElement)."]");
			}
		}
		return $js;
	}

	function	getChildDelayedJS(){

		$js = "";

		foreach($this->jsElements as $jsElement){
			if($jsElement instanceof CodeScope){
				$js .= $jsElement->getDelayedJS($this->getName());
				$js .= "\n";
			}
		}

		return $js;
	}

	function	getDelayedJS($parentScopeName){
		return "";
	}



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
	}

	function	popBracket(){
		$this->bracketCount -= 1;
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
			throw new \Exception("Trying to add default variable but not variables found yet.");
		}

		$variableName = $allKeys[count($allKeys) - 1];

		$this->defaultValues[$variableName] = convertPHPValueToJSValue($value);
	}

	function	getVariablesWithDefaultParameters(){
		return $this->defaultValues;
	}

	function	startOfFunction(){
		return FALSE;
	}

	function addStaticVariable($variableName){
		throw new \Exception("This should only be called on ClassScope");
		//Yes, I know this is terrible OO-ness.
	}

	function addPublicVariable($variableName){
		throw new \Exception("This should only be called on ClassScope");
		//Yes, I know this is terrible OO-ness.
	}

	function addToVariableValue($value){
		throw new \Exception("This should only be called on ClassScope");
		//Yes, I know this is terrible OO-ness.
	}

	function addParent($value){
		throw new \Exception("This should only be called on ClassScope");
		//Yes, I know this is terrible OO-ness.
	}
}




?>