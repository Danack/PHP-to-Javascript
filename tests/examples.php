<?php

require_once("SplClassLoader.php");

$loader = new SplClassLoader('PHPToJavascript', '../src');
$loader->register();

$exportPath = "export";

error_reporting(E_ALL);

$filesToConvert = array(
	'ArrayExample.js' => 'ArrayExample.php',
	'AssigningThis.js' => 'AssigningThis.php',
	/*'ClassExample.js' => 'ClassExample.php',
	'DefaultValue.js' => 'DefaultValue.php',

	'Inheritance.js' => 'Inheritance.php',

	'SimpleExample.js' => 'SimpleExample.php',
	'StaticTest.js' => 'StaticTest.php',
	'SwitchStatement.js' => 'SwitchStatement.php',

	'TraitExample.js' => array(
		'TraitInclude.php',
		'TraitExample.php',
	),*/

);


//'CustomEvent.php' => 'CustomEvent.js',
//	'StaticTest.php' => 'StaticTest.js',
//	'Content.php' => 'Content.js',
//	'ContentImage.php' => 'ContentImage.js',
//	'PublicPrivate.php' => 'PublicPrivate.js',



foreach($filesToConvert as $outputFilename =>  $inputFileList ){
	$phpToJavascript = new PHPToJavascript\PHPToJavascript();

	$phpToJavascript->setTrace(true);

	$phpToJavascript->setEchoConversionFunction(PHPToJavascript\PHPToJavascript::$ECHO_TO_ALERT);

	$inputFilename = "";

	if(is_array($inputFileList) == TRUE){
		foreach($inputFileList as $inputFile){
			$phpToJavascript->addFromFile($inputFile);

			$inputFilename = $inputFile." ";
		}
	}
	else{
		$phpToJavascript->addFromFile($inputFileList);
		$inputFilename = $inputFileList;
	}

	$phpToJavascript->addPostConversionReplace("//JS", "");

	$phpToJavascript->generateFile($outputFilename, $inputFilename);
}



?>