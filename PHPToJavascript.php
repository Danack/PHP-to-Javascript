<?php

if(defined('NL') == FALSE){
	define('NL', "\r\n");
}

//Control output of the state-machine trace
define("PHPToJavascript_TRACE", TRUE);
//define("PHPToJavascript_TRACE", FALSE);


require_once('TokenStream.php');
require_once('CodeScope.php');
require_once('ConverterStateMachine.php');
require_once('ConverterStates.php');


class PHPToJavascript{

	/** @var string */
	var $srcFilename;

	/**
	 * @var TokenStream
	 */
	public $tokenStream;

	/**
	 * @var ConverterStateMachine The state machine for processing the code tokens.
	 */
	public $stateMachine;

	function	__construct($srcFilename){

		$this->srcFilename = $srcFilename;
		$fileLines = file($this->srcFilename);

		$code = "";

		foreach($fileLines as $fileLine){
			$code .= $fileLine;
		}

		$this->tokenStream = new TokenStream($code);
		$this->stateMachine = new ConverterStateMachine($this->tokenStream, CONVERTER_STATE_DEFAULT);
	}

	function	toJavascript(){
		$name = '';
		$value = '';

		while($this->tokenStream->hasMoreTokens() == TRUE){
			$this->tokenStream->next($name, $value);

			$count = 0;

			do{
				$parsedToken = $this->stateMachine->parseToken($name, $value);

				$reprocess = $this->stateMachine->processToken($name, $value, $parsedToken);

				if($count > 5){
					throw new Exception("Stuck converting same token.");
				}

				$count++;
			}
			while($reprocess == TRUE);
		}

		return $this->stateMachine->finalize();
	}
}


function     generateFile($outputFilename, $originalFilename, $jsOutput) {

	$outputDirectory = pathinfo($outputFilename, PATHINFO_DIRNAME);

	ensureDirectoryExists($outputDirectory);

	$fileHandle = fopen($outputFilename, "w");

	if ($fileHandle == FALSE) {
		throw new Exception("Failed to open file [$outputFilename] for writing.");
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


?>