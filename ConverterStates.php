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

define('CONSTRUCTOR_PARAMETERS_POSITION', "/*Constructor parameters here*/");

define('CONVERTER_STATE_DEFAULT', 	'CONVERTER_STATE_DEFAULT');
define('CONVERTER_STATE_ECHO', 		'CONVERTER_STATE_ECHO');
define('CONVERTER_STATE_ARRAY', 	'CONVERTER_STATE_ARRAY');
define('CONVERTER_STATE_CLASS', 	'CONVERTER_STATE_CLASS');
define('CONVERTER_STATE_FUNCTION', 	'CONVERTER_STATE_FUNCTION');
define('CONVERTER_STATE_FOREACH', 	'CONVERTER_STATE_FOREACH');
define('CONVERTER_STATE_PUBLIC', 	'CONVERTER_STATE_PUBLIC');
define('CONVERTER_STATE_VARIABLE',  'CONVERTER_STATE_VARIABLE');
define('CONVERTER_STATE_VARIABLE_GLOBAL',  'CONVERTER_STATE_VARIABLE_GLOBAL');
define('CONVERTER_STATE_VARIABLE_FUNCTION',  'CONVERTER_STATE_VARIABLE_FUNCTION');
define('CONVERTER_STATE_VARIABLE_CLASS',  'CONVERTER_STATE_VARIABLE_CLASS');
define('CONVERTER_STATE_VARIABLE_FUNCTION_PARAMETER',  'CONVERTER_STATE_VARIABLE_FUNCTION_PARAMETER');


define('CONVERTER_STATE_STATIC', 	'CONVERTER_STATE_STATIC');
define('CONVERTER_STATE_STRING', 		'CONVERTER_STATE_STRING');
define('CONVERTER_STATE_T_PUBLIC', 		'CONVERTER_STATE_T_PUBLIC');
define('CONVERTER_STATE_T_PRIVATE', 'CONVERTER_STATE_T_PRIVATE');

define('CONVERTER_STATE_DEFINE', 'CONVERTER_STATE_DEFINE');

define('CONVERTER_STATE_T_EXTENDS', 'CONVERTER_STATE_T_EXTENDS');


define('CONVERTER_STATE_T_NEW', 'CONVERTER_STATE_T_NEW');
define('CONVERTER_STATE_T_CONSTANT_ENCAPSED_STRING', 'CONVERTER_STATE_T_CONSTANT_ENCAPSED_STRING');
define('CONVERTER_STATE_EQUALS', 'CONVERTER_STATE_EQUALS');

define('CONVERTER_STATE_CLOSE_PARENS', 'CONVERTER_STATE_CLOSE_PARENS');


define('CONVERTER_STATE_IMPLEMENTS_INTERFACE', 'CONVERTER_STATE_IMPLEMENTS_INTERFACE');
define('CONVERTER_STATE_REQUIRE', 'CONVERTER_STATE_REQUIRE');
define('CONVERTER_STATE_ABSTRACT', 'CONVERTER_STATE_ABSTRACT');
define('CONVERTER_STATE_ABSTRACT_FUNCTION', 'CONVERTER_STATE_ABSTRACT_FUNCTION');

define('CONVERTER_STATE_INTERFACE', 'CONVERTER_STATE_INTERFACE');


define('CONVERTER_STATE_END_OF_CLASS', 'CONVERTER_STATE_END_OF_CLASS');





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
		'T_ECHO' 		=> CONVERTER_STATE_ECHO,
		'T_ARRAY'		=> CONVERTER_STATE_ARRAY,
		'T_CLASS'		=> CONVERTER_STATE_CLASS,
		'T_FUNCTION'	=> CONVERTER_STATE_FUNCTION,
		'T_FOREACH'		=> CONVERTER_STATE_FOREACH,
		'T_PUBLIC'		=> CONVERTER_STATE_PUBLIC,
		'T_VARIABLE'	=> CONVERTER_STATE_VARIABLE,
		'T_STATIC'		=> CONVERTER_STATE_STATIC,
		'T_STRING'		=> CONVERTER_STATE_STRING,
		'T_VAR' 		=> CONVERTER_STATE_T_PUBLIC,
		'T_PRIVATE'		=> CONVERTER_STATE_T_PRIVATE,

		'T_EXTENDS'		=> CONVERTER_STATE_T_EXTENDS,
		'T_NEW'			=> CONVERTER_STATE_T_NEW,
		'T_CONSTANT_ENCAPSED_STRING' => CONVERTER_STATE_T_CONSTANT_ENCAPSED_STRING,
		'='					=> CONVERTER_STATE_EQUALS,
		')' 				=> CONVERTER_STATE_CLOSE_PARENS,
		'T_REQUIRE_ONCE'	=> CONVERTER_STATE_REQUIRE,
		'T_IMPLEMENTS' 		=>	CONVERTER_STATE_IMPLEMENTS_INTERFACE,

		'T_ABSTRACT' 		=>	CONVERTER_STATE_ABSTRACT,

		'T_INTERFACE'		=> CONVERTER_STATE_INTERFACE,
	);

	function	processToken($name, $value, $parsedToken){
		if($name == 'T_STRING'){
			if($value == 'define'){
				$this->changeToState(CONVERTER_STATE_DEFINE);
				return TRUE;
			}
		}

		if(array_key_exists($name, $this->tokenStateChangeList) == TRUE){
			$this->changeToState($this->tokenStateChangeList[$name]);
			return TRUE;
		}

		$js = $parsedToken;
		$this->stateMachine->addJS($js);

		if($name == '{'){
			if($this->stateMachine->currentScope->startOfFunction() == TRUE){
				$this->stateMachine->addDefaultsForVariables();
			}
		}


//		if($name == '{'){
//			if($this->stateMachine->currentScope->endOfClass() == TRUE){
//				$this->stateMachine->addClassBindingMagic();
//			}
//		}


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
	var				$stateChunk = '';

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->stateChunk = '';
	}



	function	processToken($name, $value, $parsedToken){		//until ;

		if($name == ')'){//This code needs refactoring - as $_keep is not safe, as parens are stateful.
			$parsedToken = ')';
		}

		if(array_key_exists($parsedToken, $this->arraySymbolRemap) == TRUE){
			$parsedToken = $this->arraySymbolRemap[$parsedToken];//change name to other value
		}

		//TODO - this needs to go through the scope for a variable name
		$parsedToken = str_replace("$", "", $parsedToken);

		if($name == "T_CONSTANT_ENCAPSED_STRING"){
			$this->stateChunk .= $value;
		}
		else{
			$this->stateChunk .= $parsedToken;
		}

		if($name == ';'){
			$js = $this->stateChunk;

			if (strpos($js, ':') === FALSE) {

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
			$this->stateMachine->pushScope(CODE_SCOPE_CLASS, $value);



			$this->stateMachine->addJS("function $value(".CONSTRUCTOR_PARAMETERS_POSITION.")");
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}

class CodeConverterState_FUNCTION extends CodeConverterState {

	function	processToken($name, $value, $parsedToken){

		if($name == "T_STRING"){

			$previousScope = $this->stateMachine->currentScope;

			$this->stateMachine->pushScope(CODE_SCOPE_FUNCTION_PARAMETERS, $value, $this->stateMachine->variableFlags);

			if($previousScope instanceof ClassScope){
				//$this->stateMachine->markMethodsStart();
				//echo "Gaah";
				$previousScope->markMethodsStart();

				if($this->stateMachine->variableFlags & DECLARATION_TYPE_PRIVATE){
					$this->stateMachine->addJS("function $value ");
				}
				else{
					$this->stateMachine->addJS(PUBLIC_FUNCTION_MARKER_MAGIC_STRING."$value = function ");
				}

				//$this->stateMachine->addJS("this.$value = function");
			}
			else{
				//if($this->stateMachine->variableFlags & DECLARATION_TYPE_PRIVATE){
					$this->stateMachine->addJS("function $value ");
//				}
//				else{
//					$this->stateMachine->addJS(PUBLIC_FUNCTION_MARKER_MAGIC_STRING."$value = function ");
//				}
			}

			/*
			if($value == "__construct"){
				$this->stateMachine->markConstructorStart();
			}
			*/

			$this->stateMachine->clearVariableFlags();
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


/*
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
} */

class CodeConverterState_T_VARIABLE extends CodeConverterState {

	function	processToken($name, $value, $parsedToken){

		if($this->stateMachine->currentScope instanceof GlobalScope){
			$this->changeToState(CONVERTER_STATE_VARIABLE_GLOBAL);
			return TRUE;
		}

		if($this->stateMachine->currentScope instanceof FunctionScope){
			//TODO - Double-check FunctionParameterScope is meant to be the same as FunctionScope
			$this->changeToState(CONVERTER_STATE_VARIABLE_FUNCTION);
			return TRUE;
		}

		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
			$this->changeToState(CONVERTER_STATE_VARIABLE_FUNCTION_PARAMETER);
			return TRUE;
		}

		if($this->stateMachine->currentScope instanceof ClassScope){
			$this->changeToState(CONVERTER_STATE_VARIABLE_CLASS);
			return TRUE;
		}
	}
}





class CodeConverterState_T_VARIABLE_GLOBAL extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {
		$variableName = cVar($value);


		$this->stateMachine->addScopedVariable($variableName, 0);
		$this->stateMachine->addJS($variableName);

		$this->stateMachine->clearVariableFlags();

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}

class CodeConverterState_T_VARIABLE_FUNCTION extends CodeConverterState {

	var $isClassVariable;

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->isClassVariable = FALSE;
	}

	function    processToken($name, $value, $parsedToken) {

		if($value == '$this'){
			$this->isClassVariable = TRUE;
			return;
		}

		if($name == 'T_OBJECT_OPERATOR'){
			//This is skipped as private class variables are converted from
			// "$this->varName" to "varName" - for the joy of Javascript scoping.
			return;
		}

//

		$variableName = cVar($value);

//		if($this->stateMachine->currentScope == CODE_SCOPE_FUNCTION_PARAMETERS){
//			$this->stateMachine->currentScope->addParameterName($variableName);
//		}

		if($this->stateMachine->variableFlags & DECLARATION_TYPE_STATIC){
			$scopeName = $this->stateMachine->getScopeName();
			$this->stateMachine->addJS("if (typeof ".$scopeName.".$variableName == 'undefined')\n ");
		}

		if($this->isClassVariable == FALSE){ //Don't add class variables to the function scope
			$this->stateMachine->addScopedVariable($variableName, $this->stateMachine->variableFlags);
		}

		$scopedVariableName = $this->stateMachine->getVariableNameForScope(/*CODE_SCOPE_FUNCTION,*/ $variableName, $this->isClassVariable);

//		if($this->isClassVariable == TRUE){
//			$scopedVariableName = $this->stateMachine->getVariableNameForScope(/*CODE_SCOPE_CLASS,*/ $variableName, $this->isClassVariable);
//		}
//		else{
//			$scopedVariableName = $this->stateMachine->getVariableNameForScope(/*CODE_SCOPE_FUNCTION,*/ $variableName, $this->isClassVariable);
//		}

		$this->stateMachine->addJS($scopedVariableName);

		$this->isClassVariable = FALSE;
		$this->stateMachine->clearVariableFlags();
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


class CodeConverterState_T_VARIABLE_PARAMETER extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {

	//
		$variableName = cVar($value);

		//$this->stateMachine->currentScope->addParameterName($variableName);

//
		$this->stateMachine->addScopedVariable($variableName, $this->stateMachine->variableFlags);
		//$scopedVariableName = $this->stateMachine->getVariableNameForScope(CODE_SCOPE_FUNCTION_PARAMETERS, $variableName, FALSE);
		//$scopedVariableName = $variableName;
		$this->stateMachine->addJS($variableName);
		//$this->stateMachine->clearVariableFlags();
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}




class CodeConverterState_T_VARIABLE_CLASS extends CodeConverterState {

	function    processToken($name, $value, $parsedToken) {


		$variableName = cVar($value);

		if($this->stateMachine->variableFlags & DECLARATION_TYPE_PRIVATE){
			$this->stateMachine->addJS("var ");
		}
		else if($this->stateMachine->variableFlags & DECLARATION_TYPE_STATIC){
			//$this->stateMachine->addJS($this->stateMachine->currentScope->getName().".");
			$this->stateMachine->addJS("var ");
		}
		else if($this->stateMachine->variableFlags & DECLARATION_TYPE_PUBLIC){
			$this->stateMachine->addJS("this.");
			//$this->stateMachine->addJS($this->stateMachine->currentScope->getName().".");
		}

		$this->stateMachine->addScopedVariable($variableName, $this->stateMachine->variableFlags);
		$this->stateMachine->addJS($variableName);

		$this->stateMachine->clearVariableFlags();
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


class CodeConverterState_T_STATIC extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		if($this->stateMachine->variableFlags & DECLARATION_TYPE_NEW){
			$this->stateMachine->addJS($this->stateMachine->getClassName());
		}
		else{
			$this->stateMachine->variableFlags |= DECLARATION_TYPE_STATIC;
		}
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



class CodeConverterState_T_PUBLIC extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->variableFlags |= DECLARATION_TYPE_PUBLIC;
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


class CodeConverterState_T_PRIVATE extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->variableFlags |= DECLARATION_TYPE_PRIVATE;
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


class CodeConverterState_T_EXTENDS  extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		if($name == T_STRING){
			echo "Need to grab variables/functions from [$value]";
		}

		if($name == '{'){
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return TRUE;
		}
	}
}

class CodeConverterState_T_IMPLEMENTS_INTERFACE  extends CodeConverterState{

	public $first = FALSE;

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->first = TRUE;
	}

	function	processToken($name, $value, $parsedToken){
		if($this->first == TRUE){
			$this->first = FALSE;
			$this->stateMachine->addJS("/*");
		}

		if($name == 'T_STRING' || $name == 'T_WHITESPACE'){
			$this->stateMachine->addJS($value);
		}

		if($name == '{'){
			$this->stateMachine->addJS("*/");
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return TRUE;
		}
	}
}


class CodeConverterState_T_INTERFACE  extends CodeConverterState{

	public $first = FALSE;

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->first = TRUE;
	}

	function	processToken($name, $value, $parsedToken){
		if($this->first == TRUE){
			$this->first = FALSE;
			$this->stateMachine->addJS("/*");
		}

		if($name == 'T_STRING' || $name == 'T_WHITESPACE'){
			$this->stateMachine->addJS($value);
		}
		else{
			$this->stateMachine->addJS($name);
		}

		if($name == '}'){
			$this->stateMachine->addJS("}*/");
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}



class CodeConverterState_T_STRING extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
//			echo "misunderstood.";
		}


		$defineValue = $this->stateMachine->getDefine($value);

		if($defineValue !== FALSE){
			$this->stateMachine->addJS("'".$defineValue."'");
		}
		else if(strcmp('static', $value) == 0 ||
				strcmp('self', $value) == 0){
			$this->stateMachine->addJS($this->stateMachine->getClassName());
		}
		else if($this->stateMachine->currentScope instanceof FunctionParameterScope){
			$this->stateMachine->addJS( "/*". $value ."*/");
			$this->stateMachine->currentScope->setDefaultValueForPreviousVariable($value);
		}
		else{
			$this->stateMachine->addJS($value);
		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


class CodeConverterState_define extends CodeConverterState{

	var $defineName;

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->defineName = FALSE;
		$this->stateMachine->addJS("// ");
	}

	function	processToken($name, $value, $parsedToken){

		$this->stateMachine->addJS($parsedToken); //Maybe should be parsedToken

		if($name == 'T_CONSTANT_ENCAPSED_STRING'){
			if($this->defineName == FALSE){
				$this->defineName = $value;
			}
			else{
				$this->stateMachine->addDefine($this->defineName, $value);
				$this->changeToState(CONVERTER_STATE_DEFAULT);
			}
		}
	}
}




class CodeConverterState_T_NEW  extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->addJS('new');
		$this->stateMachine->addVariableFlags(DECLARATION_TYPE_NEW);
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



class CodeConverterState_T_CONSTANT_ENCAPSED_STRING extends CodeConverterState{
	function	processToken($name, $value, $parsedToken){
		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
			$this->stateMachine->addJS( "/*". $value ."*/");
			$this->stateMachine->currentScope->setDefaultValueForPreviousVariable($value);
		}
		else{
			$this->stateMachine->addJS($value);
		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}

class CodeConverterState_Equals extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
			//Don't add an equals - default parameters are set inside the JS function
		}
		else{
			$this->stateMachine->addJS($name);
		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


class CodeConverterState_CLOSE_PARENS extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
			$this->stateMachine->pushScope(
				CODE_SCOPE_FUNCTION,
				$this->stateMachine->currentScope->getName()
			);
		}

		$this->stateMachine->addJS(')');

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


/*class CodeConverterState_IMPLEMENTS_INTERFACE extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
//		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
//			$this->stateMachine->pushScope(
//				CODE_SCOPE_FUNCTION,
//				$this->stateMachine->currentScope->getName()
//			);
//		}
//
//		$this->stateMachine->addJS(')');

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}*/

class CodeConverterState_REQUIRE extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->addJS('//'.$value);
		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



class CodeConverterState_T_ABSTRACT extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		if($this->stateMachine->currentScope instanceof GlobalScope){
			//Do nothing - abstract classes don't affect JS code generation.
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}

		if($this->stateMachine->currentScope instanceof ClassScope){
			//Abstract functions inside a class are commented out
			$this->changeToState(CONVERTER_STATE_ABSTRACT_FUNCTION);
			return TRUE;
		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



class CodeConverterState_T_ABSTRACT_REMOVE extends CodeConverterState{

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->first = TRUE;

		$this->stateMachine->addJS("//");
	}

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->addJS('//'.$value);

		if($name == ';'){
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}

class CodeConverterState_EndOfClass extends CodeConverterState{

	var $previousScope = NULL;

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->previousScope = $extraParams['previousScope'];
	}

	function	processToken($name, $value, $parsedToken){
		if($name == '}'){
			$this->stateMachine->addJS('}'."\n\n");
			$className = $this->previousScope->name;
//			$this->stateMachine->addJS("$className = new $className(/*Constuctor for static methods+vars*/);"."\n\n");
		}
//		else{
//			throw new Exception( "Only token } should be getting here.");
//		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}


?>