<?php

define('DECLARATION_TYPE_STATIC', 0x1);
define('DECLARATION_TYPE_PRIVATE', 0x2);
define('DECLARATION_TYPE_PUBLIC', 0x4);
define('DECLARATION_TYPE_CLASS', 0x8);

define('DECLARATION_TYPE_NEW', 0x10);

define('METHOD_MARKER_MAGIC_STRING', "/* METHODS HERE */");

/** @var array these token keys will be converted to their values */
$_convert = array (
	'T_IS_EQUAL'=>'==',
	'T_IS_GREATER_OR_EQUAL'=>'>=',
	'T_IS_SMALLER_OR_EQUAL'=>'<=',
	'T_IS_IDENTICAL'=>'===',
	'T_IS_NOT_EQUAL'=>'!=',
	'T_IS_NOT_IDENTICAL'=>'!==',
	'T_IS_SMALLER_OR_EQUA'=>'<=',
	'T_BOOLEAN_AND'=>'&&',
	'T_BOOLEAN_OR'=>'||',
	'T_CONCAT_EQUAL'=>'+= ',
	'T_DIV_EQUAL'=>'/=',
	'T_DOUBLE_COLON'=>'.',
	'T_INC'=>'++',
	'T_MINUS_EQUAL'=>'-=',
	'T_MOD_EQUAL'=>'%=',
	'T_MUL_EQUAL'=>'*=',
	'T_OBJECT_OPERATOR'=>'.',
	'T_OR_EQUAL'=>'|=',
	'T_PLUS_EQUAL'=>'+=',
	'T_SL'=>'<<',
	'T_SL_EQUAL'=>'<<=',
	'T_SR'=>'>>',
	'T_SR_EQUAL'=>'>>=',
	'T_START_HEREDOC'=>'<<<',
	'T_XOR_EQUAL'=>'^=',
	//'T_NEW'=>'new',
	'T_ELSE'=>'else',
	'.'=>' + ',
	'T_IF'=>'if',
	'T_RETURN'=>'return',
	'T_AS'=>'in',
	'T_WHILE'=>'while',
	'T_LOGICAL_AND' => 'AND',
	'T_LOGICAL_OR' => 'OR',
	'T_LOGICAL_XOR' => 'XOR',
	'T_EVAL' => 'eval',
	'T_ELSEIF' => 'else if',
	'T_BREAK' => 'break',
	'T_DOUBLE_ARROW' => ':',
);

/** @var array these tokens stays the same */
$_keep = array(
	//'=',
	',', '}', '{',
	';', '(', //')',
	'*',
	'/', '+', '-', '>',
	'<', '[', ']', "\"",
	"'",
);

/** @var array these tokens keeps their value */
$_keepValue = array (
	//'T_CONSTANT_ENCAPSED_STRING',
	'T_STRING', 'T_COMMENT',
	'T_ML_COMMENT',
	'T_DOC_COMMENT',
	'T_LNUMBER',
	'T_ENCAPSED_AND_WHITESPACE',
	'T_WHITESPACE',
);


function	unencapseString($string){

	if($string[0] == '"' ||
		$string[0] == "'"){
		$string = substr($string, 1);
	}

	$stringLength = strlen($string);

	if ($string[$stringLength - 1] == '"' ||
		$string[$stringLength - 1] == "'"){
		$string = substr($string, 0, -1);
	}

	return $string;
}



class	ConverterStateMachine{

	/**
	 * @var CodeConverterState
	 */
	public $currentState;

	/**
	 * @var string[]
	 */
	private $jsArray = array();

	/**
	 * @var CodeConverterState[]
	 */
	public $states = array();

	/**
	 * @var bool Are we inside static var or function?
	 */
	public $variableFlags = FALSE;

	/**
	 * @var TokenStream
	 */
	public $tokenStream;

	public $pendingSymbols = array();

	/** @var CodeScope */
	public $currentScope = NULL;

	/** @var CodeScope[] */
	public $scopesStack = array();


	public $constructorInfoArray = array();

	public $constructorStartIndex = 0;
	public $methodsStartIndex = 0;

	public $defines = array();

	function	__construct($tokenStream, $defaultState){

		$this->tokenStream = $tokenStream;

		$this->pushScope(CODE_SCOPE_GLOBAL, 'GLOBAL');

		$this->states[CONVERTER_STATE_DEFAULT] = new CodeConverterState_Default($this);
		$this->states[CONVERTER_STATE_ECHO] = new CodeConverterState_Echo($this);
		$this->states[CONVERTER_STATE_ARRAY] = new CodeConverterState_ARRAY($this);
		$this->states[CONVERTER_STATE_CLASS] = new CodeConverterState_CLASS($this);
		$this->states[CONVERTER_STATE_FUNCTION] = new CodeConverterState_FUNCTION($this);
		//$this->states[CONVERTER_STATE_FUNCTION_NAME] = new CodeConverterState_FUNCTION_NAME($this);

		$this->states[CONVERTER_STATE_FOREACH] = new CodeConverterState_T_FOREACH($this);
		$this->states[CONVERTER_STATE_PUBLIC] = new CodeConverterState_T_PUBLIC($this);
		$this->states[CONVERTER_STATE_VARIABLE] = new CodeConverterState_T_VARIABLE($this);

		$this->states[CONVERTER_STATE_VARIABLE_GLOBAL] = new CodeConverterState_T_VARIABLE_GLOBAL($this);
		$this->states[CONVERTER_STATE_VARIABLE_FUNCTION] = new CodeConverterState_T_VARIABLE_FUNCTION($this);
		$this->states[CONVERTER_STATE_VARIABLE_CLASS] = new CodeConverterState_T_VARIABLE_CLASS($this);

		$this->states[CONVERTER_STATE_VARIABLE_FUNCTION_PARAMETER] = new CodeConverterState_T_VARIABLE_PARAMETER($this);

		$this->states[CONVERTER_STATE_STATIC] = new CodeConverterState_T_STATIC($this);
		$this->states[CONVERTER_STATE_STRING] = new CodeConverterState_T_STRING($this);

		$this->states[CONVERTER_STATE_T_PUBLIC] = new CodeConverterState_T_PUBLIC($this);
		$this->states[CONVERTER_STATE_T_PRIVATE] = new CodeConverterState_T_PRIVATE($this);

		$this->states[CONVERTER_STATE_DEFINE] = new CodeConverterState_define($this);

		$this->states[CONVERTER_STATE_T_EXTENDS] = new CodeConverterState_T_EXTENDS($this);
		$this->states[CONVERTER_STATE_T_NEW] = new CodeConverterState_T_NEW($this);

		$this->states[CONVERTER_STATE_T_CONSTANT_ENCAPSED_STRING] = new CodeConverterState_T_CONSTANT_ENCAPSED_STRING($this);
		$this->states[CONVERTER_STATE_EQUALS] = new CodeConverterState_Equals($this);

		$this->states[CONVERTER_STATE_CLOSE_PARENS] = new CodeConverterState_CLOSE_PARENS($this);


		$this->currentState = $defaultState;
	}

	function	addScopedVariable($variableName, $variableFlags){
		$this->currentScope->addScopedVariable($variableName, $variableFlags);
	}

	function	getVariableNameForScope(/*$scopeType,*/ $variableName, $isClassVariable){
		//if($this->currentScope->type == $scopeType){
			return $this->currentScope->getScopedVariable($variableName, $isClassVariable);
		//}

//		$scope = $this->findScopeType($scopeType);
//		if($scope != NULL){
//			$return = $scope->getScopedVariable($variableName, $isClassVariable);
//			//echo "variableName $variableName isClassVariable $isClassVariable return $return \n";
//			return $return;
//		}

		//return $variableName;
	}

	function	findScopeType($type){
		foreach($this->scopesStack as $scope){
			if($scope->getType() == $type){
				return $scope;
			}
		}

		return NULL;
	}


	function	getJSArray(){
		return $this->jsArray;
	}

	function getJS($startIndex, $endIndex){
		$return = '';

		for ($x=$startIndex ; $x<$endIndex ; $x++){
			$return .= $this->jsArray[$x];
		}

		return $return;
	}

	public function addJS($jsString){
		$this->jsArray[] = $jsString;
	}

	function	changeToState($newState){
		if(array_key_exists($newState, $this->states) == FALSE){
			throw new Exception("Unknown state [$newState], cannot changeState to it.");
		}

		$this->currentState = $newState;
		$this->states[$this->currentState]->enterState();
	}

	function	clearVariableFlags(){
		$this->variableFlags = FALSE;
	}

	function	addVariableFlags($variableFlag){
		$this->variableFlags |= DECLARATION_TYPE_NEW;
	}

	function	processToken($name, $value, $parsedToken){
		if(PHPToJavascript_TRACE == TRUE){
			echo "SM ".get_class($this->getState())." token [$name] => [$value]  ".NL;
		}
		return $this->getState()->processToken($name, $value, $parsedToken);
	}

	function	getState(){
		return $this->states[$this->currentState];
	}

	function parseToken ($name, $value) {

		$returnValue = $this->getPendingInsert($name);

		if($name == "{"){
			$this->pushBracket();
		}

		if($name == "}"){
			$scopeEnded = $this->currentScope->popBracket();



			if ($scopeEnded == TRUE){
				$poppedScope = $this->currentScope;

				$this->popCurrentScope();	//It was the last bracket for a function.

				if($poppedScope instanceof FunctionScope){
					$this->popCurrentScope();//Also pop the function paramters scope.
				}
			}


		}

		if($name == "T_VARIABLE"){
			$returnValue .= $value;
		}
		else if (in_array($name, array_keys($GLOBALS['_convert']))) {
			if(empty($GLOBALS['_convert'][$name]) == TRUE){
				$returnValue .= $name;		//keep key
			}
			else{
				$returnValue .= $GLOBALS['_convert'][$name];
			}
		}
		else if (in_array($name, $GLOBALS['_keep'])) {	//keep value
			$returnValue .= $name;
		}
		else if($name == 'T_STRING' && defined($value)){
			$returnValue .= constant($value);
		}
		else if (in_array($name, $GLOBALS['_keepValue'])) {
			$returnValue .= $value;
		}

		return $returnValue;
	}


	function	getPendingInsert($symbolToCheck){

		foreach($this->pendingSymbols as $key => $pendingSymbol){

			$symbol = $pendingSymbol[0];
			$insert = $pendingSymbol[1];

			if($symbolToCheck == $symbol){
				unset($this->pendingSymbols[$key]);
				return $insert;
			}
		}

		return '';
 	}


	function	setPendingSymbol($symbol, $insert){
		$this->pendingSymbols[] = array($symbol, $insert);
	}

	function	getScopeType(){
		return $this->currentScope->type;
	}

	function	getScopeName(){
		return $this->currentScope->getName();
	}

	function	pushScope($type, $name){
		if($this->currentScope != NULL){
			array_push($this->scopesStack, $this->currentScope);
		}

		switch($type){
			case(CODE_SCOPE_GLOBAL):{
				$newScope = new GlobalScope($name, $this->currentScope);
				break;
			}

			case(CODE_SCOPE_CLASS):{
				$newScope = new ClassScope($name, $this->currentScope);
				break;
			}

			case(CODE_SCOPE_FUNCTION_PARAMETERS):{
				$newScope = new FunctionParameterScope($name, $this->currentScope);
				break;
			}

			case(CODE_SCOPE_FUNCTION):{
				$newScope = new FunctionScope($name, $this->currentScope);
				break;
			}

			default:{
				throw new Exception("Unknown scope type [".$type."]");
				break;
			}
		}

		$this->currentScope = $newScope;

		if($type == CODE_SCOPE_CLASS){
			$this->methodsStartIndex = 0;
		}
	}

	function	popCurrentScope(){
		//Do something with $this->currentScope before destroying it?

		$constructorEndIndex = 0;

		if($this->constructorStartIndex != 0){
			$constructorEndIndex = count($this->jsArray);;
		}

		$this->currentScope = array_pop($this->scopesStack);

		if(($this->currentScope instanceof ClassScope) &&
			$constructorEndIndex != 0){
			$this->constructorInfoArray[] = array(
				$this->currentScope->name,
				$this->constructorStartIndex,
				$constructorEndIndex
			);
			$this->constructorStartIndex = 0;
		}
	}


	function	finalize(){
		$code =  implode('', $this->getJSArray());

		foreach($this->constructorInfoArray as $constructorInfo){
			$className = $constructorInfo[0];
			$startIndex = $constructorInfo[1];
			$endIndex = $constructorInfo[2];

			$search = $className."()";
			$constructor = $this->getJS($startIndex, $endIndex);

			$firstBracketPosition = strpos($constructor, '{');

			$constructorDeclaration = substr($constructor, 0, $firstBracketPosition);

			$constructorBody = substr($constructor, $firstBracketPosition + 1);

			$code = str_replace($search, $className.$constructorDeclaration, $code);

			//$code = str_replace(METHOD_MARKER_MAGIC_STRING, $constructorBody, $code);
		}

		$code = str_replace(METHOD_MARKER_MAGIC_STRING, '', $code);

		return $code;
	}

	function	markConstructorStart(){
		$this->constructorStartIndex = count($this->jsArray);
	}

	function	markMethodsStart(){
		if($this->methodsStartIndex == 0){
			$this->methodsStartIndex = count($this->jsArray);
			$this->addJS(NL.METHOD_MARKER_MAGIC_STRING.NL);
		}
	}

	function	addDefine($name, $value){
		$name = unencapseString($name);
		$value = unencapseString($value);

		$this->defines[$name] = $value;
	}

	function	getDefine($name){
		if(array_key_exists($name, $this->defines) == TRUE){
			return $this->defines[$name];
		}

		return FALSE;
	}

	function	getClassName(){
		$scope = $this->findScopeType(CODE_SCOPE_CLASS);
		if($scope != NULL){
			return $scope->name;
		}

		throw new Exception("Trying to get class but no class scope found.");
	}


	function	pushBracket(){
		$this->currentScope->pushBracket();
	}

	function	addDefaultsForVariables(){
		$functionParametersScope = $this->findScopeType(CODE_SCOPE_FUNCTION_PARAMETERS);

		if($functionParametersScope == NULL){
			throw new Exception("We're inside a function but we can't find the CODE_SCOPE_FUNCTION_PARAMETERS - that shouldn't be possible.");
		}

		$variablesWithDefaultParameters = $functionParametersScope->getVariablesWithDefaultParameters();

		foreach($variablesWithDefaultParameters as $variable => $default){
			$jsString = "\n";
			$jsString .= "\t\tif(typeof $variable === \"undefined\"){\n";
			$jsString .= "\t\t\t$variable = $default;\n";
			$jsString .= "\t\t}\n";

			$this->addJS($jsString);
		}
	}
}








?>