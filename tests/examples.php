<?php

require_once("SplClassLoader.php");

$loader = new SplClassLoader('PHPToJavascript', '../src');
$loader->register();

$exportPath = "export";

error_reporting(E_ALL);

$filesToConvert = array(


	'DefaultValue.php' => 'DefaultValue.js',

//	'Inheritance.php' => 'Inheritance.js',
//
	//'CustomEvent.php' => 'CustomEvent.js',
//	'StaticTest.php' => 'StaticTest.js',
//	'Content.php' => 'Content.js',
//	'ContentImage.php' => 'ContentImage.js',
//	'PublicPrivate.php' => 'PublicPrivate.js',

	'SimpleExample.php' => 'SimpleExample.js',
	'SwitchStatement.php' => 'SwitchStatement.js',
	'Trait.php' => 'Trait.js',
);


foreach($filesToConvert as $inputFilename => $outputFilename){
	$phpToJavascript = PHPToJavascript\PHPToJavascript::createFromFile($inputFilename);

	$phpToJavascript->setEchoConversionFunction(PHPToJavascript\PHPToJavascript::$ECHO_TO_ALERT);

	$phpToJavascript->addPostConversionReplace("//JS", "");

	$phpToJavascript->generateFile($outputFilename, $inputFilename);
}



?>