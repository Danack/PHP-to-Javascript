<?php


function cVar($var) {
	return  str_replace('$', '', $var);
}

define('CONVERTER_STATE_DEFAULT', 	'CONVERTER_STATE_DEFAULT');
define('CONVERTER_STATE_ECHO', 		'CONVERTER_STATE_ECHO');
define('CONVERTER_STATE_ARRAY', 	'CONVERTER_STATE_ARRAY');
define('CONVERTER_STATE_CLASS', 	'CONVERTER_STATE_CLASS');
define('CONVERTER_STATE_FUNCTION', 	'CONVERTER_STATE_FUNCTION');
define('CONVERTER_STATE_FOREACH', 	'CONVERTER_STATE_FOREACH');
define('CONVERTER_STATE_PUBLIC', 	'CONVERTER_STATE_PUBLIC');
define('CONVERTER_STATE_VARIABLE',  'CONVERTER_STATE_VARIABLE');
define('CONVERTER_STATE_STATIC', 	'CONVERTER_STATE_STATIC');


class	ConverterStateMachine{

	/**
	 * @var CodeConverterState
	 */
	public $state;

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

	function	__construct($defaultState, $isClassScope){

		$this->isClassScope = $isClassScope;

		$this->states[CONVERTER_STATE_DEFAULT] = new CodeConverterState_Default($this);
		$this->states[CONVERTER_STATE_ECHO] = new CodeConverterState_Echo($this);
		$this->states[CONVERTER_STATE_ARRAY] = new CodeConverterState_ARRAY($this);
		$this->states[CONVERTER_STATE_CLASS] = new CodeConverterState_CLASS($this);
		$this->states[CONVERTER_STATE_FUNCTION] = new CodeConverterState_FUNCTION($this);
		$this->states[CONVERTER_STATE_FOREACH] = new CodeConverterState_T_FOREACH($this);
		$this->states[CONVERTER_STATE_PUBLIC] = new CodeConverterState_T_PUBLIC($this);
		$this->states[CONVERTER_STATE_VARIABLE] = new CodeConverterState_T_VARIABLE($this);
		$this->states[CONVERTER_STATE_STATIC] = new CodeConverterState_T_STATIC($this);
	}

	function	addScopedVariable($variableName){
		if($this->currentMethodName == FALSE){
			throw new Exception("Trying to addScopedVariable [$variableName] but current method name is FALSE");
		}

		$this->scopedVariables[$variableName] =  $this->currentMethodName.".$variableName";
	}

	function	getScopedVariable($variableName){
		if(array_key_exists($variableName, $this->scopedVariables) == TRUE){
			return $this->scopedVariables[$variableName];
		}

		return $variableName;
	}

	function	setCurrentMethodName($value){
		$this->currentMethodName = $value;
	}

	function	resetStateVariables(){
		$this->currentMethodName = FALSE;
		$this->scopedVariables = array();
		//$this->staticVariable = FALSE; HMM
	}

	function	getJSArray(){
		return $this->jsArray;
	}

	public function addJS($jsString){
		$this->jsArray[] = $jsString;
	}

	function	changeToState($newState){

		$oldState = $this->state;

		if($newState != CONVERTER_STATE_DEFAULT &&
			$this->state != CONVERTER_STATE_DEFAULT){
			echo "Mild astonishment! Current state ".$this->state." new state $newState - neither is 'CONVERTER_STATE_DEFAULT'".NL;
		}

		if(array_key_exists($newState, $this->states) == FALSE){
			throw new Exception("Unknown state [$newState], cannot changeState to it.");
		}
		else{
			$this->state = $this->states[$newState];
		}

		$this->resetStateVariables();

		if($oldState == CONVERTER_STATE_VARIABLE){
			$this->stateMachine->staticVariable = FALSE;
		}
	}

	function	processToken($name, $value){
		echo "SM ".get_class($this->state)" token [$name] => [$value]  ".NL
		return $this->state->processToken($name, $value);
	}
}


abstract class CodeConverterState{

	/**
	 * @var ConverterStateMachine
	 */
	protected $stateMachine = NULL;

	function __construct(ConverterStateMachine $stateMachine){
		$this->stateMachine = $stateMachine;
		//$this->tokenStream = $tokenStream;
	}

	function	changeToState($newState){
		$this->stateMachine->changeToState($newState);
	}

	public function		enterState($extraParams = array()){

	}

	/**
	 * @param $name
	 * @param $value
	 * @return bool Whether the token should be reprocessed by the new state
	 */
	abstract function	processToken($name, $value);
	public function		exitState($extraParams){}

	/** @var array these token keys will be converted to their values */
	private $_convert = array (
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
	private $_keep = array(
		'=', ',', '}', '{',
		';', '(', ')', '*',
		'/', '+', '-', '>',
		'<', '[', ']', "\"",
		"'",
	);

	/** @var array these tokens keeps their value */
	private $_keepValue = array (
		'T_CONSTANT_ENCAPSED_STRING',
		'T_STRING', 'T_COMMENT',
		'T_ML_COMMENT',
		'T_DOC_COMMENT',
		'T_LNUMBER',
		'T_ENCAPSED_AND_WHITESPACE',
		'T_WHITESPACE',
	);


	protected function parseToken ($name, $value, $symbolRenameArray = array()) {

		//custom changes
		if (in_array($name, array_keys((array)$symbolRenameArray))) {
			return $symbolRenameArray[$name];//change name to other value
		}
		else if (in_array($name, array_keys($this->_convert))) {
			return (!empty($this->_convert[$name])) ? $this->_convert[$name] : $name;
			//keep key
		}
		else if (in_array($name, $this->_keep)) {	//keep value
			return $name;
		}
		else if($name == 'T_STRING' && defined($value)){
			return constant($value);
		}
		else if (in_array($name, $this->_keepValue)) {
			return $value;
		}
		else if (method_exists($this, $name)) {
			return $this->$name($value);
		}

		return '';
	}
}


class CodeConverterState_Default extends CodeConverterState {

	/**
	 * @var array List of tokens that will trigger a change to the appropriate state.
	 */
	public $tokenStateChangeList = array(
		'T_ECHO' 		=>	CONVERTER_STATE_ECHO,
		'T_ARRAY'		=>	CONVERTER_STATE_ARRAY,
		'T_CLASS'		=>	CONVERTER_STATE_CLASS,
		'T_FUNCTION'	=>	CONVERTER_STATE_FUNCTION,
		'T_FOREACH'		=>	CONVERTER_STATE_FOREACH,
		'T_PUBLIC'		=>	CONVERTER_STATE_PUBLIC,
		'T_VARIABLE'	=>	CONVERTER_STATE_VARIABLE,
		'T_STATIC'		=>	CONVERTER_STATE_STATIC,
	);


	function	processToken($name, $value){
		if(array_key_exists($name, $this->tokenStateChangeList) == TRUE){
			$this->changeToState($this->tokenStateChangeList[$name]);
			return TRUE;
		}

		$js = $this->parseToken($name, $value);
		$this->stateMachine->addJS($js);
		return FALSE;
	}
}




class CodeConverterState_Echo extends CodeConverterState {

	function	processToken($name, $value){		//until ;

		$this->stateChunk .= $this->parseToken($name, $value);

		if($name == ';'){
			$this->stateMachine->addJS('document.write('.trim($this->stateChunk).');');
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}

		return FALSE;
	}
}




class CodeConverterState_ARRAY extends CodeConverterState {

	private  		$arraySymbolRemap = array('('=>'{',	')'=>'}',);

	function	processToken($name, $value){		//until ;
		$this->stateChunk .= $this->parseToken($name, $value, $this->arraySymbolRemap);

		if($name == ';'){
			$js = $this->stateChunk;

			if (strpos($js, ':') === FALSE) {
				$this->tmp = -1;
				$js = preg_replace_callback ('/([{, \t\n])(\'.*\')(|.*:(.*))([,} \t\n])/Uis', array($this, 'cb_T_ARRAY'), $js);
			}

			$this->stateMachine->addJS($this->stateChunk);
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}

	private function cb_T_ARRAY($_matches) {
		$this->tmp++;
		if (strpos($_matches[0], ':') === FALSE) {
			return ($_matches[1].$this->tmp.':'.$_matches[2].$_matches[3].$_matches[4].$_matches[5]);
		} else {
			return $_matches[0];
		}
	}
}



class CodeConverterState_CLASS extends CodeConverterState {


	function	processToken($name, $value){//Skip 2 tokens
		$this->stateMachine->addJS("function $value()");
		$this->skipTokens(2);

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}

class CodeConverterState_FUNCTION extends CodeConverterState {

	function	processToken($name, $value){//Skip 2 tokens

		//Used for generating static vars in javascript.
		$this->stateMachine->setCurrentMethodName($value);

		if($this->stateMachine->isClassScope == TRUE){
			return "this.$value = function";
		}
		else{
			return "function $value ";
		}

		$this->skipTokens(2);
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}

class CodeConverterState_T_FOREACH extends CodeConverterState {

	var $chunkArray = array();

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->chunkArray = '';
	}

	function	processToken($name, $value){//till the {

		if ($name == 'T_VARIABLE'){
			$this->chunkArray[] = cVar($value);
		}

		if ($name == '{') {
			if (count($this->chunkArray) == 2) {
				$array = $this->chunkArray[0];
				$val = $this->chunkArray[1];
				$this->stateMachine->addJS( "for (var {$val}Val in $array) {".
					"		\n                        $val = $array"."[{$val}Val];");
			}
			if (count($this->chunkArray) == 3) {
				$array = $this->chunkArray[0];
				$key = $this->chunkArray[1];
				$val = $this->chunkArray[2];
				$this->stateMachine->addJS("for (var $key in $array) {".
					"\n                        $val = $array"."[$key];");
			}
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}


class CodeConverterState_T_PUBLIC extends CodeConverterState {

	var	$stateChunk = '';

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->stateChunk = '';
	}

	function	processToken($name, $value){//till ; or =

		$type = $this->findFirst(array('T_VARIABLE', 'T_FUNCTION'));

		if ($type == 'T_FUNCTION'){
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return;
		}

		$this->stateChunk .= $this->parseToken($name, $value);

		if ($name == ';') {
			$js = str_replace(array(' '), '', $this->stateChunk);
			$result = 'this.'.$this->stateChunk;
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return $result;
		}

		if($name == '='){
			$this->changeToState('Default');
			$js = str_replace(array(' ','='), '', $this->stateChunk);
			$result = 'this.'.$js.' =';
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return $result;
		}
	}
}

class CodeConverterState_T_VARIABLE extends CodeConverterState {

	//Change back immediately.
	function	processToken($name, $value){
		$variableName = cVar($value);

		if($this->stateMachine->staticVariable == TRUE){
			$javascript = "if (typeof ".$this->stateMachine->currentMethodName.".$variableName == 'undefined') ";
			$javascript .= $this->stateMachine->currentMethodName.".$variableName";
			$this->stateMachine->addScopedVariable($variableName, $this->currentMethodName);
			$this->stateMachine->addJS($javascript);
		}
		else{
			$javascript = $this->stateMachine->getScopedVariable($variableName);
			$this->stateMachine->addJS($javascript);
		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}

class CodeConverterState_T_STATIC extends CodeConverterState{

	function	processToken($name, $value){
		$this->stateMachine->staticVariable = TRUE;
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}

?>