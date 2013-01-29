<?php

define('CODE_SCOPE_GLOBAL', 'CODE_SCOPE_GLOBAL');
define('CODE_SCOPE_FUNCTION', 'CODE_SCOPE_FUNCTION');
define('CODE_SCOPE_FUNCTION_PARAMETERS', 'CODE_SCOPE_FUNCTION_PARAMETERS');
define('CODE_SCOPE_CLASS', 'CODE_SCOPE_CLASS');


define("CONSTRUCTOR_POSITION_MARKER", "/*CONSTRUCTOR GOES HERE*/");

function trimConstructor($constructor){

	$constructorInfo = array();

	$firstBracketPosition = strpos($constructor, '(');
	$closeBracketPosition = strpos($constructor, ')', $firstBracketPosition + 1);

	$firstParensPosition = strpos($constructor, '{');
	$lastParensPosition = strrpos($constructor, '}');

	if($firstParensPosition === FALSE ||
		$lastParensPosition === FALSE){
		echo "Could not figure out brackets [".$constructor."]";
		exit(0);
	}

	$constructorInfo['parameters'] = substr($constructor, $firstBracketPosition + 1, $closeBracketPosition - ($firstBracketPosition + 1) );

	$constructorInfo['body'] = substr($constructor, $firstParensPosition + 1, $lastParensPosition - ($firstParensPosition + 1) );

	return $constructorInfo;
}

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

	var $childScopes = array();

	function addChild($scope){
		//$this->childScopes[] = $scope;
		$this->jsElements[] = $scope;
	}

	var $jsElements = array();

	function	addJS($jsString){
		$this->jsElements[] = $jsString;
	}


	function	getJS(){
		return $this->getJSRaw();
	}

	function	markMethodsStart(){
		//echo "HUH";
		throw new Exception("This should only be called on ClassScope");
	}

	function	getJSRaw(){
		//$js = "\n//Beginning of scope ".get_class($this)." ".$this->getName()."\n";

		$js = "";

		foreach($this->jsElements as $jsElement){
			if($jsElement instanceof CodeScope){
				$js .= $jsElement->getJS();
			}
			else if(is_string($jsElement)){
				$js .= $jsElement;
			}
			else{
				throw new Exception("Unknown type in this->jsElements of type [".get_class($jsElement)."]");
			}
		}

		//$js .= "\n//End of scope ".get_class($this)." ".$this->getName()."\n";

		$js .= "\n";
		$js .= "\n";

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
		//echo "bracket count ".$this->name ." = ".$this->bracketCount."\n";
	}

	function	popBracket(){
		$this->bracketCount -= 1;

		//echo "bracket count  ".$this->name." = " .$this->bracketCount."\n";

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

	var		$methodsStartIndex = FALSE;

	function getType(){
		return CODE_SCOPE_CLASS;
	}


	function	getJS(){
		$jsRaw = $this->getJSRaw();

		$constructor = FALSE;

		foreach($this->jsElements as $jsElement){
			if($jsElement instanceof CodeScope){
				if($jsElement->getName() == '__construct'){
					$constructor = $jsElement->getJSRaw();
					break;
				}
			}
		}

		if($constructor !== FALSE){
			$constructorInfo = trimConstructor($constructor);
			$jsRaw = str_replace(CONSTRUCTOR_PARAMETERS_POSITION, $constructorInfo['parameters'], $jsRaw);
			$jsRaw = str_replace(CONSTRUCTOR_POSITION_MARKER, $constructorInfo['body'], $jsRaw);
		}

		return $jsRaw;
	}

	function	markMethodsStart(){

		//echo "WUT - ".$this->methodsStartIndex."\n";

		if($this->methodsStartIndex === FALSE){
			$this->methodsStartIndex = count($this->jsElements);
		//	echo "really";


			$this->addJS(CONSTRUCTOR_POSITION_MARKER);
		}
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

	var $variableFlag = 0;

	function	__construct($name, $parentScope, $variableFlag){
		parent::__construct($name, $parentScope);
		$this->variableFlag = $variableFlag;
	}

	function	getDelayedJS($parentScopeName){

		if($this->getName() == "__construct"){
			//constructor gets included in ClassScope
			return "";
		}

		if(($this->variableFlag & DECLARATION_TYPE_PRIVATE) == 0){
			$jsRaw = $this->getJSRaw();

			if(($this->variableFlag & DECLARATION_TYPE_STATIC)){
				$jsRaw = str_replace(PUBLIC_FUNCTION_MARKER_MAGIC_STRING, $parentScopeName.".", $jsRaw);
			}
			else{
				$jsRaw = str_replace(PUBLIC_FUNCTION_MARKER_MAGIC_STRING, $parentScopeName.".prototype.", $jsRaw);
			}
			return $jsRaw;
		}
	}

	function	getJS(){

		if($this->getName() == "__construct"){
			//constructor gets included in ClassScope
			return "";
		}

		if(($this->variableFlag & DECLARATION_TYPE_PRIVATE) != 0){
			return $this->getJSRaw();
		}
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