<?php

require_once("SplClassLoader.php");

$loader = new SplClassLoader('PHPToJavascript', '../src');
$loader->register();

$exportPath = "export";

error_reporting(E_ALL);

$filesToConvert = array(

	'TraitExample.js' => array('TraitInclude.php', 'TraitExample.php',),

	'DefaultValue.js' => 'DefaultValue.php',

//	'Inheritance.php' => 'Inheritance.js',
//
	//'CustomEvent.php' => 'CustomEvent.js',
//	'StaticTest.php' => 'StaticTest.js',
//	'Content.php' => 'Content.js',
//	'ContentImage.php' => 'ContentImage.js',
//	'PublicPrivate.php' => 'PublicPrivate.js',

	'SimpleExample.js' => 'SimpleExample.php',
	'SwitchStatement.js' => 'SwitchStatement.php',

);


foreach($filesToConvert as $outputFilename =>  $inputFileList ){
	$phpToJavascript = new PHPToJavascript\PHPToJavascript();

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