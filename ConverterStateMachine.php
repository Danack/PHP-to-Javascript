<?php


define('DECLARATION_TYPE_STATIC', 0x1);
define('DECLARATION_TYPE_PRIVATE', 0x2);
define('DECLARATION_TYPE_PUBLIC', 0x4);
define('DECLARATION_TYPE_CLASS', 0x8);


define('METHOD_MARKER_MAGIC_STRING', "/* METHODS HERE */");

//Objective corporation

//Brand screen

//Bullet proof - ux engineer


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
	'T_NEW'=>'new',
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
	'=', ',', '}', '{',
	';', '(', ')', '*',
	'/', '+', '-', '>',
	'<', '[', ']', "\"",
	"'",
);

/** @var array these tokens keeps their value */
$_keepValue = array (
	'T_CONSTANT_ENCAPSED_STRING',
	'T_STRING', 'T_COMMENT',
	'T_ML_COMMENT',
	'T_DOC_COMMENT',
	'T_LNUMBER',
	'T_ENCAPSED_AND_WHITESPACE',
	'T_WHITESPACE',
);




class	ConverterStateMachine{

	/**
	 * @var CodeConverterState
	 */
	public $currentState;

	/**
	 * @var bool Are we inside a class? Has implications for
	 */
	public $isClassScope;

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

	function	__construct($tokenStream, $defaultState, $isClassScope){

		$this->tokenStream = $tokenStream;
		$this->isClassScope = $isClassScope;

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

		$this->states[CONVERTER_STATE_STATIC] = new CodeConverterState_T_STATIC($this);
		$this->states[CONVERTER_STATE_STRING] = new CodeConverterState_T_STRING($this);

		$this->states[CONVERTER_STATE_T_PUBLIC] = new CodeConverterState_T_PUBLIC($this);
		$this->states[CONVERTER_STATE_T_PRIVATE] = new CodeConverterState_T_PRIVATE($this);

		$this->currentState = $defaultState;
	}

	function	skipTokens($skipCount){
		$this->tokenStream->skipTokens($skipCount);
	}

	function	addScopedVariable($variableName, $variableFlags){
		$this->currentScope->addScopedVariable($variableName, $variableFlags);
	}

//	function	getScopedVariableName($variableName){
//		return $this->currentScope->getScopedVariable($variableName);
//	}

	function	getVariableNameForScope($scopeType, $variableName, $isClassVariable){

		if($this->currentScope->type == $scopeType){
			return $this->currentScope->getScopedVariable($variableName, $isClassVariable);
		}

		foreach($this->scopesStack as $scope){
			if($scope->type == $scopeType){
				return $scope->getScopedVariable($variableName, $isClassVariable);
			}
		}



		return $variableName;
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
	}

	function	clearVariableFlags(){
		$this->variableFlags = FALSE;
	}

	function	processToken($name, $value, $parsedToken){
		echo "SM ".get_class($this->getState())." token [$name] => [$value]  ".NL;
		return $this->getState()->processToken($name, $value, $parsedToken);
	}

	function	getState(){
		return $this->states[$this->currentState];
	}

	function parseToken ($name, $value) {

		$returnValue = $this->getPendingInsert($name);

		if($name == "{"){
			$this->currentScope->pushBracket();
		}

		if($name == "}"){
			$scopeEnded = $this->currentScope->popBracket();

			if ($scopeEnded == TRUE){
				$this->popCurrentScope();
			}
		}

		if($name == "T_VARIABLE"){
			//throw new Exception("This shouldn't happen.");
			//$returnValue .= $this->getScopedVariableName($value);
			$returnValue .= $value;
		}
		else
			if (in_array($name, array_keys($GLOBALS['_convert']))) {
			$returnValue .= (!empty($GLOBALS['_convert'][$name])) ? $GLOBALS['_convert'][$name] : $name;
			//keep key
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

//		if($type == CODE_SCOPE_FUNCTION){
//			if($this->currentScope->type == CODE_SCOPE_CLASS){
//				$this->markMethodsStart();
//			}
//		}


		$this->currentScope = new CodeScope($type, $name, $this->currentScope);

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

		if($this->currentScope->type == CODE_SCOPE_CLASS && $constructorEndIndex != 0){
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

			//echo "Replace constructor ".$className." ".$startIndex." ".$endIndex.NL;

			$search = $className."()";
			$constructor = $this->getJS($startIndex, $endIndex);

			$firstBracketPosition = strpos($constructor, '{');

			$constructorDeclaration = substr($constructor, 0, $firstBracketPosition);

			$constructorBody = substr($constructor, $firstBracketPosition + 1);

			$code = str_replace($search, $className.$constructorDeclaration, $code);

			$code = str_replace(METHOD_MARKER_MAGIC_STRING, $constructorBody, $code);
		}

		return $code;
	}

	function	markConstructorStart(){
		$this->constructorStartIndex = count($this->jsArray);

		//echo "constructorStartIndex ".$this->constructorStartIndex.NL;
	}

	function	markMethodsStart(){

		if($this->methodsStartIndex == 0){
			$this->methodsStartIndex = count($this->jsArray);
			$this->addJS(NL.METHOD_MARKER_MAGIC_STRING.NL);


		}
	}



}





?>