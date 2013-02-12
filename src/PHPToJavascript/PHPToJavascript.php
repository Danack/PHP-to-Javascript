<?php

namespace PHPToJavascript;

if(defined('NL') == FALSE){
	define('NL', "\r\n");
}

//Control output of the state-machine trace
//define("PHPToJavascript_TRACE", TRUE);
define("PHPToJavascript_TRACE", FALSE);

define('CODE_SCOPE_GLOBAL', 'CODE_SCOPE_GLOBAL');
define('CODE_SCOPE_FUNCTION', 'CODE_SCOPE_FUNCTION');
define('CODE_SCOPE_FUNCTION_PARAMETERS', 'CODE_SCOPE_FUNCTION_PARAMETERS');
define('CODE_SCOPE_CLASS', 'CODE_SCOPE_CLASS');

define("CONSTRUCTOR_POSITION_MARKER", "/*CONSTRUCTOR GOES HERE*/");


define('DECLARATION_TYPE_STATIC', 0x1);
define('DECLARATION_TYPE_PRIVATE', 0x2);
define('DECLARATION_TYPE_PUBLIC', 0x4);
define('DECLARATION_TYPE_CLASS', 0x8);

define('DECLARATION_TYPE_NEW', 0x10);

define('METHOD_MARKER_MAGIC_STRING', "/* METHODS HERE */");
define('PUBLIC_FUNCTION_MARKER_MAGIC_STRING', 'PUBLIC METHOD HERE');


function cVar($var) {
	return  str_replace('$', '', $var);
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
define('CONVERTER_STATE_VARIABLE_DEFAULT', 'CONVERTER_STATE_T_CONSTANT_ENCAPSED_STRING');
define('CONVERTER_STATE_EQUALS', 'CONVERTER_STATE_EQUALS');
define('CONVERTER_STATE_CLOSE_PARENS', 'CONVERTER_STATE_CLOSE_PARENS');
define('CONVERTER_STATE_IMPLEMENTS_INTERFACE', 'CONVERTER_STATE_IMPLEMENTS_INTERFACE');
define('CONVERTER_STATE_REQUIRE', 'CONVERTER_STATE_REQUIRE');
define('CONVERTER_STATE_ABSTRACT', 'CONVERTER_STATE_ABSTRACT');
define('CONVERTER_STATE_ABSTRACT_FUNCTION', 'CONVERTER_STATE_ABSTRACT_FUNCTION');
define('CONVERTER_STATE_INTERFACE', 'CONVERTER_STATE_INTERFACE');
define('CONVERTER_STATE_END_OF_CLASS', 'CONVERTER_STATE_END_OF_CLASS');
define('CONVERTER_STATE_VARIABLE_VALUE', 'CONVERTER_STATE_VARIABLE_VALUE');
define('CONVERTER_STATE_OBJECT_OPERATOR', 'CONVERTER_STATE_OBJECT_OPERATOR');


/**
 * Converts a PHP constructor into the parameter string and then body, so that it
 * can be inlined to Javascript style constructors.
 *
 * @param $constructor
 * @return array
 */
function trimConstructor($constructor){

	$constructorInfo = array();

	$firstBracketPosition = strpos($constructor, '(');
	$closeBracketPosition = strpos($constructor, ')', $firstBracketPosition + 1);

	$firstParensPosition = strpos($constructor, '{');
	$lastParensPosition = strrpos($constructor, '}');

	if($firstParensPosition === FALSE ||
		$lastParensPosition === FALSE){
		//My Parens are deaaaaad - batman.
		throw new Exception("Could not figure out brackets for constructor [".$constructor."]. Either your code is malformed or something really hinkey is going on.");

	}

	$constructorInfo['parameters'] = substr($constructor, $firstBracketPosition + 1, $closeBracketPosition - ($firstBracketPosition + 1) );

	$constructorInfo['body'] = substr($constructor, $firstParensPosition + 1, $lastParensPosition - ($firstParensPosition + 1) );

	return $constructorInfo;
}

/**
 * Some string constants are spelt differently in PHP to Javascript. Convert between them.
 *
 * @param $value
 * @return string
 */
function	convertPHPValueToJSValue($value){

	if($value == 'FALSE'){
		return 'false';
	}

	if($value == 'TRUE'){
		return 'true';
	}

	if($value == 'NULL'){
		return 'null';
	}

	if($value == 'Exception'){
		//Add other exceptions here
		return 'Error';
	}

	return $value;
}




class PHPToJavascript{

	public static $ECHO_TO_ALERT = 'alert(';
	public static $ECHO_TO_DOCUMENT_WRITE = 'document.write(';

	/** @var string */
	var $srcFilename;

	/**
	 * @var TokenStream
	 */
	public $tokenStream;

	public $postConversionReplacements = array();

	/**
	 * @var ConverterStateMachine The state machine for processing the code tokens.
	 */
	public $stateMachine;

	/**
	 * Please use either createFromFile or createFromString
	 */
	private function	__construct(){
	}


	static function createFromFile($srcFilename){

		$phpToJavascript = new self();

		$phpToJavascript->srcFilename = $srcFilename;
		$fileLines = file($phpToJavascript->srcFilename);

		if($fileLines == FALSE){
			throw new \Exception("Failed to open srcFilename [$srcFilename]");
		}

		$code = "";

		foreach($fileLines as $fileLine){
			$code .= $fileLine;
		}

		$phpToJavascript->initFromString($code);

		return $phpToJavascript;
	}


	static function createFromString($code){
		$phpToJavascript = new self();
		$phpToJavascript->initFromString($code);

		return $phpToJavascript;
	}

	function initFromString($code){
		$this->tokenStream = new TokenStream($code);
		$this->stateMachine = new ConverterStateMachine($this->tokenStream, CONVERTER_STATE_DEFAULT);
	}


	/**
	 * Set what echo function in PHP is converted to. The trailing bracket "(" on the function is required.
	 *
	 * @param $echoConversionFunction. This should take the form of "callableJavascriptFunction("
	 *
	 * TODO - should support callback function here to allow context sensitive replacement.
	 */
	function setEchoConversionFunction($echoConversionFunction){
		CodeConverterState_Echo::setEchoConversionFunction($echoConversionFunction);
	}


	function	toJavascript(){
		$name = '';
		$value = '';

		while($this->tokenStream->hasMoreTokens() == TRUE){
			$this->tokenStream->next($name, $value);

			$count = 0;

			do{
				$parsedToken = $this->stateMachine->parseToken($name, $value, $count);

				if($count == 0){
					$this->stateMachine->accountForOpenBrackets($name);
				}

				$reprocess = $this->stateMachine->processToken($name, $value, $parsedToken);

				if($count == 0){
					$this->stateMachine->accountForCloseBrackets($name);
				}

				if($count > 5){
					throw new \Exception("Stuck converting same token.");
				}

				$count++;
			}
			while($reprocess == TRUE);

			if($name == 'T_VARIABLE'){
				if($this->stateMachine->insertToken != FALSE){
					$this->stateMachine->addJS($this->stateMachine->insertToken);
					$this->stateMachine->insertToken = FALSE;
				}
			}
		}

		$output = $this->stateMachine->finalize();

		$searchArray = array_keys($this->postConversionReplacements);
		$replaceArray = array_values($this->postConversionReplacements);

		$output = str_replace($searchArray, $replaceArray, $output);

		return $output;
	}

	function addPostConversionReplace($search, $replace){
		$this->postConversionReplacements[$search] = $replace;
	}



	function     generateFile($outputFilename, $originalFilename) {

		$jsOutput = $this->toJavascript();

		$outputDirectory = pathinfo($outputFilename, PATHINFO_DIRNAME);

		$this->ensureDirectoryExists($outputDirectory);

		$fileHandle = fopen($outputFilename, "w");

		if ($fileHandle == FALSE) {
			throw new \Exception("Failed to open file [$outputFilename] for writing.");
		}

		fwrite($fileHandle, "//Auto-generated file by PHP-To-Javascript at ".date(DATE_RFC822).NL);
		fwrite($fileHandle, "//\n");
		fwrite($fileHandle, "//DO NOT EDIT - all changes will be lost.\n");
		fwrite($fileHandle, "//\n");
		fwrite($fileHandle, "//Please edit the file " . $originalFilename . " and then reconvert to make any changes\n");
		fwrite($fileHandle, "\n");

		fwrite($fileHandle, $jsOutput);

		fclose($fileHandle);
	}


	function ensureDirectoryExists($filePath) {

		$pathSegments = array();

		$slashPosition = 0;
		$finished = FALSE;

		while ($finished === FALSE) {
			$slashPosition = strpos($filePath, '/', $slashPosition + 1);
			if ($slashPosition === FALSE) {
				$finished = TRUE;
			} else {
				$pathSegments[] = substr($filePath, 0, $slashPosition);
			}

			if (count($pathSegments) > 10) {
				$finished = FALSE;
			}
		}

		foreach ($pathSegments as $segment) {
			if (file_exists($segment) === FALSE) {
				$directoryCreated = mkdir($segment);

				if ($directoryCreated == FALSE) {
					throw new \Exception("Failed to create directory $filePath");
				}
			}
		}
	}
}


/**
 * I hate PHPs 'feature' of not throwing an error when you set a variable for a class that doesn't exist.
 * e.g. I meant to type:
 * $this->isValidated = true;
 * but accidentally type
 * $this->isVlidated = true;
 *
 * As you spend an hour trying to find out why isValidated isn't being set. This trait turns all bad
 * get and set calls on non-existent variables into exceptions.
 */
trait SafeAccess {
	public function __set($name, $value) {
		throw new \Exception("Property [$name] doesn't exist for class [".__CLASS__."] so can set it");
	}
	public function __get($name) {
		throw new \Exception("Property [$name] doesn't exist for class [".__CLASS__."] so can get it");
	}
}


?>