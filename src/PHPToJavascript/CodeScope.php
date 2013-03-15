<?php

namespace PHPToJavascript;


abstract class CodeScope{

	use SafeAccess;

	var $bracketCount = 0;

	var $name;

	var $defaultValues = array();

	/** @var CodeScope */
	var $parentScope;

	var $jsElements = array();

	function addChild($scope){
		$this->jsElements[] = $scope;
	}

	function	addJS($jsString){
		$this->jsElements[] = $jsString;
	}

	function	getJS(){
		$js = "";
		foreach($this->jsElements as $jsElement){
			if($jsElement instanceof CodeScope){
				$js .= $jsElement->getJS();
			}
			else if(is_string($jsElement)){
				$js .= $jsElement;
			}
		}

		return $js;
	}

	function	markMethodsStart(){
		throw new \Exception("This should only be called on ClassScope");
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

	function	getScopedVariable($variableName,  $variableFlags, $originalScope){

		$isClassVariable = ($variableFlags & DECLARATION_TYPE_CLASS);
		$result = $this->getScopedVariableForScope($variableName, $isClassVariable);

		if($result == NULL){
			if($this->parentScope != NULL){
				$result = $this->parentScope->getScopedVariable($variableName, $isClassVariable, $variableFlags, FALSE);
			}
		}

		if($originalScope == TRUE){
			if($result == FALSE) {
				if(($variableFlags & DECLARATION_TYPE_CLASS) == 0){
					//First use of variable in a function - lets add a 'var' to make Javascript happy.
					$this->addScopedVariable($variableName, $variableFlags);
					$result = "var $variableName";
				}
				else{
					//The variable really ought to exist in the class scope
					throw new Exception("Ought of order use for varaible [".$variableName."]. Please have your variables above your methods. It makes life easier.");
				}
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

	function	pushParens(){
		//does nothing
	}

	function	popParens(){
		//Does nothings
		return FALSE;
	}

	/**
	 * Adds a variable to the scope
	 *
	 * @param $variableName
	 * @param $variableFlag
	 * @return bool true if it was a new variable to this scope.
	 */
	function	addScopedVariable($variableName, $variableFlag){

		if($variableFlag & DECLARATION_TYPE_CLASS){
			//In cases lile "$this->variableName" variableName is never
			//added to the scope.
			return false;
		}

		$cVar = cvar($variableName);

		if(PHPToJavascript::$TRACE == TRUE){
			echo "Added variable $variableName to scope ".get_class($this)."\n";
		}

		if(array_key_exists($cVar, $this->scopedVariables) == FALSE){
			$this->scopedVariables[$cVar] = $variableFlag;
			return TRUE;
		}
		return FALSE;
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



	/**
	 * For class variables that are added to the class scope, but are delayed to be delcared outside
	 * the function (to be public or static) we need to grab the default values to be able to set


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

	function	getInPlaceJS(){
		return $this->getJS();
	}


	//Contains hacks
	function	preStateMagic($name, $value){

	}

	//Contains hacks
	function	postStateMagic($name, $value){
	}

	function	findAncestorScopeByType($type){

		if($this->parentScope == null){
			return null;
		}

		if($this->parentScope->getType() == $type){
			return $this->parentScope;
		}

		return $this->parentScope->findAncestorScopeByType($type);
	}
}




?>