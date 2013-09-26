<?php

namespace PHPToJavascript;


abstract class CodeScope{

	use SafeAccess;

	var $bracketCount = 0;

	var $name;

	var $defaultValues = array();

    /**
     * @var Variable[]
     */
    protected $scopedVariables = array();

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
	 * @param $variableName
	 * @param $variableFlags
	 * @return mixed
	 *
	 * For a given variable name, try to find the variable in the current scope.
	 */
	abstract	function	getScopedVariableForScope($variableName, $variableFlags);
	abstract	function getType();

    function getVariable($variableName){
        if (array_key_exists($variableName, $this->scopedVariables)) {
            return $this->scopedVariables[$variableName];
        }
        return null;
    }
    
    

	function	getScopedVariable($variableName, $variableFlags, $originalScope){

		$result = $this->getScopedVariableForScope($variableName, $variableFlags);

		if($result == NULL){
			if($this->parentScope != NULL){
				$result = $this->parentScope->getScopedVariable($variableName, $variableFlags, FALSE);
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
                    //But maybe it's been declared after it's use or is a SomeClass::param
                    $result = $variableName;
				}
			}
		}

		return $result;
	}

    
    function getVariableFromScopeInternal($variableName) {
        if (array_key_exists($variableName, $this->scopedVariables)) {
            return $this->scopedVariables[$variableName];
        }

        return null;
    }
    
    
    function getVariableFromScope($variableName) {

        $result = $this->getVariableFromScopeInternal($variableName);
        
        if($result == NULL){
            if($this->parentScope != NULL){
                $result = $this->parentScope->getVariableFromScope($variableName);
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

		if($variableFlag & DECLARATION_TYPE_CLASS) {
            if (!($variableFlag & DECLARATION_TYPE_PRIVATE)) {
                //In cases like "$this->variableName" variableName is never
                //added to the scope.
                return false;
            }
		}

		$cVar = cvar($variableName);

		if(PHPToJavascript::$TRACE == TRUE){
			echo "Added variable $variableName to scope ".get_class($this)." with flag $variableFlag\n";
		}

		if(array_key_exists($cVar, $this->scopedVariables) == FALSE){
            $variable = new Variable($cVar, $variableFlag);
			$this->scopedVariables[$cVar] = $variable;
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

//	function	getJSRaw(){
//		$js = "";
//		$js .= $this->getJS_InPlace();
//		$js .= "\n";
//		$js .= $this->getEndOfScopeJS();
//		$js .= "\n";
//		$js .= $this->getChildDelayedJS();
//
//
//
//
//
//		return $js;
//	}

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


    function	addToJsForPreviousVariable($value) {
        throw new \Exception("This has no default implementation.");
    }

    function previousTokensMatch($tokens) {

        $tokens = array_reverse($tokens);//Tokens are matched from the end.
        
        $position = count($this->jsElements) - 1;
        
        foreach ($tokens as $token) {
            if (strcmp($token, $this->jsElements[$position]) != 0) {
                return false;
            }
            $position--;
        }
        return true;
    }

    function deleteTokens($tokenCountToDelete) {
        for ($x=0 ; $x<$tokenCountToDelete ; $x++) {
            $discardedToken = array_pop($this->jsElements);
        }
    }
}

