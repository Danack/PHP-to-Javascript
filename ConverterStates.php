<?php


function cVar($var) {
	return  str_replace('$', '', $var);
}

function cb_T_ARRAY($_matches) {
	$this->tmp++;
	if (strpos($_matches[0], ':') === FALSE) {
		return ($_matches[1].$this->tmp.':'.$_matches[2].$_matches[3].$_matches[4].$_matches[5]);
	} else {
		return $_matches[0];
	}
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
//define('CONVERTER_STATE_SKIP', 		'CONVERTER_STATE_SKIP');



abstract class CodeConverterState{

	/**
	 * @var ConverterStateMachine
	 */
	protected $stateMachine = NULL;

	function __construct(ConverterStateMachine $stateMachine){
		$this->stateMachine = $stateMachine;
	}

	function	changeToState($newState){
		$this->stateMachine->changeToState($newState);
	}

	public function		enterState($extraParams = array()){
	}
	public function		exitState($extraParams){
	}

	/**
	 * @param $name
	 * @param $value
	 * @return bool Whether the token should be reprocessed by the new state
	 */
	abstract function	processToken($name, $value, $parsedToken);

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


	function	processToken($name, $value, $parsedToken){
		if(array_key_exists($name, $this->tokenStateChangeList) == TRUE){
			$this->changeToState($this->tokenStateChangeList[$name]);
			return TRUE;
		}

		$js = $parsedToken;// $this->parseToken($name, $value);
		$this->stateMachine->addJS($js);
		return FALSE;
	}
}




class CodeConverterState_Echo extends CodeConverterState {

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
	}

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->addJS('document.write('.$parsedToken);
		$this->stateMachine->setPendingSymbol(';', ")");
		$this->changeToState(CONVERTER_STATE_DEFAULT);
		return FALSE;
	}
}


class CodeConverterState_ARRAY extends CodeConverterState {

	private  		$arraySymbolRemap = array('('=>'{',	')'=>'}',);

	function	processToken($name, $value, $parsedToken){		//until ;

		if(array_key_exists($parsedToken, $this->arraySymbolRemap) == TRUE){
			 $parsedToken = $this->arraySymbolRemap[$parsedToken];//change name to other value
		}

		$this->stateChunk .= $parsedToken;

		if($name == ';'){
			$js = $this->stateChunk;

			if (strpos($js, ':') === FALSE) {
				//$this->tmp = -1;
				$js = preg_replace_callback ('/([{, \t\n])(\'.*\')(|.*:(.*))([,} \t\n])/Uis', 'cb_T_ARRAY', $js);
			}

			$this->stateMachine->addJS($js);
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}



class CodeConverterState_CLASS extends CodeConverterState {

	function	processToken($name, $value, $parsedToken){
		if($name == "T_STRING"){
			$this->stateMachine->addJS("function $value()");
			$this->changeToState(CONVERTER_STATE_SKIP);
		}
	}
}

class CodeConverterState_FUNCTION extends CodeConverterState {

	function	processToken($name, $value, $parsedToken){

		if($name == "T_STRING"){
			$this->stateMachine->beginParsingMethod($value);

			if($this->stateMachine->isClassScope == TRUE){
				$this->stateMachine->addJS("this.$value = function");
			}
			else{
				$this->stateMachine->addJS("function $value ");
			}

			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}



class CodeConverterState_T_FOREACH extends CodeConverterState {

	var $chunkArray = array();

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->chunkArray = '';
	}

	//till the {
	function	processToken($name, $value, $parsedToken){

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

	function	processToken($name, $value, $parsedToken){

		$type = $this->findFirst(array('T_VARIABLE', 'T_FUNCTION'));

		if ($type == 'T_FUNCTION'){
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return;
		}

		//$parsedToken = $this->parseToken($name, $value);
		$this->stateChunk .= $parsedToken;

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
	function	processToken($name, $value, $parsedToken){
		$variableName = cVar($value);

		if($this->stateMachine->staticVariable == TRUE){
			//This variable is right after the keyword static - add the JS equivalent
			$javascript = "if (typeof ".$this->stateMachine->currentMethodName.".$variableName == 'undefined') ";
			$javascript .= $this->stateMachine->currentMethodName.".$variableName";
			$this->stateMachine->addScopedVariable($variableName);
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

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->staticVariable = TRUE;
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}

//
//class CodeConverterState_Skip extends CodeConverterState{
//
//	private $tokensToSkip = 0;
//
//	public function		enterState($extraParams = array()){
//		$this->tokensToSkip = $extraParams['tokensToShip'];
//	}
//
//	function	processToken($name, $value, $parsedToken){
//		$this->tokensToSkip--;
//
//		if($this->tokensToSkip <= 0){
//			$this->changeToState(CONVERTER_STATE_DEFAULT);
//		}
//	}
//}



?>