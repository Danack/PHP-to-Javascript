<?php


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
	 * @var string
	 */
	public $currentMethodName;

	/**
	 * @var string[]
	 */
	public $scopedVariables = array();

	/**
	 * @var bool Are we inside static var or function?
	 */
	public $staticVariable = FALSE;

	/**
	 * @var TokenStream
	 */
	public $tokenStream;

	public $pendingSymbols = array();


	/** @var int counts the number of brackets so that we can tell when we exit a function block */
	public $bracketCounter = 0;

	function	__construct($tokenStream, $defaultState, $isClassScope){

		$this->tokenStream = $tokenStream;
		$this->isClassScope = $isClassScope;

		$this->states[CONVERTER_STATE_DEFAULT] = new CodeConverterState_Default($this);
		$this->states[CONVERTER_STATE_ECHO] = new CodeConverterState_Echo($this);
		$this->states[CONVERTER_STATE_ARRAY] = new CodeConverterState_ARRAY($this);
		$this->states[CONVERTER_STATE_CLASS] = new CodeConverterState_CLASS($this);
		$this->states[CONVERTER_STATE_FUNCTION] = new CodeConverterState_FUNCTION($this);
		//$this->states[CONVERTER_STATE_FUNCTION_NAME] = new CodeConverterState_FUNCTION_NAME($this);

		$this->states[CONVERTER_STATE_FOREACH] = new CodeConverterState_T_FOREACH($this);
		$this->states[CONVERTER_STATE_PUBLIC] = new CodeConverterState_T_PUBLIC($this);
		$this->states[CONVERTER_STATE_VARIABLE] = new CodeConverterState_T_VARIABLE($this);
		$this->states[CONVERTER_STATE_STATIC] = new CodeConverterState_T_STATIC($this);

		$this->currentState = $defaultState;
	}

	function	skipTokens($skipCount){
		$this->tokenStream->skipTokens($skipCount);
	}


	function	addScopedVariable($variableName){
		if($this->currentMethodName == FALSE){
			throw new Exception("Trying to addScopedVariable [$variableName] but current method name is FALSE");
		}

		$this->scopedVariables[$variableName] =  $this->currentMethodName.".$variableName";
	}

	function	getScopedVariable($variableName){

		$cVar = cvar($variableName);

		if(array_key_exists($cVar, $this->scopedVariables) == TRUE){
			return $this->scopedVariables[$cVar];
		}

		return $variableName;
	}

	//Used for generating static vars in javascript.
	function	beginParsingMethod($value){
		$this->currentMethodName = $value;

		if($this->bracketCounter != 0){
			throw new Exception("bracketCounter is not zero - how can this be.");
		}

		$this->bracketCounter = 0;

		$this->scopedVariables = array();
	}

	function	endParsingMethod(){
		$this->currentMethodName = FALSE;
		$this->bracketCounter = 0;

		$this->scopedVariables = array();
	}

	function	incrementBracketCounter(){
		if($this->currentMethodName != FALSE){
			$this->bracketCounter++;
		}
	}

	function	decrementBracketCounter(){
		if($this->currentMethodName != FALSE){
			$this->bracketCounter--;

			if($this->bracketCounter <= 0){
				$this->endParsingMethod();
			}
		}
	}


	function	resetStateVariables(){
		//$this->currentMethodName = FALSE; this ought to be reset somewhere
//		$this->scopedVariables = array();
		//$this->staticVariable = FALSE; HMM
	}

//	function	endParsingMethod(){
//		$this->scopedVariables = array();
//	}

	function	getJSArray(){
		return $this->jsArray;
	}

	public function addJS($jsString){
		$this->jsArray[] = $jsString;
	}

	function	changeToState($newState){

		$oldState = $this->currentState;

		if($newState != CONVERTER_STATE_DEFAULT &&
			$this->currentState != CONVERTER_STATE_DEFAULT){
			echo "Mild astonishment! Current state ".$this->currentState." new state $newState - neither is 'CONVERTER_STATE_DEFAULT'".NL;
		}

		if(array_key_exists($newState, $this->states) == FALSE){
			throw new Exception("Unknown state [$newState], cannot changeState to it.");
		}

		$this->currentState = $newState;

		$this->resetStateVariables();

		if($oldState == CONVERTER_STATE_VARIABLE){
			$this->staticVariable = FALSE;
		}
	}

	function	processToken($name, $value, $parsedToken){
		echo "SM ".get_class($this->getState())." token [$name] => [$value]  ".NL;
		return $this->getState()->processToken($name, $value, $parsedToken);
	}

	function	getState(){
		return $this->states[$this->currentState];
	}

	function parseToken ($name, $value) {

		$returnValue =

		$returnValue = $this->getPendingInsert($name);

		if($name == "T_VARIABLE"){
			$returnValue .= $this->getScopedVariable($value);
		}
		else if (in_array($name, array_keys($GLOBALS['_convert']))) {
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

}





?>