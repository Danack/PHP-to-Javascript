<?php

require_once("SplClassLoader.php");

$loader = new SplClassLoader('PHPToJavascript', '../src');
$loader->register();

$exportPath = "export";

error_reporting(E_ALL);

$filesToConvert = array(

	'Inheritance.php' => 'Inheritance.js',

//	'BaseRealityEvent.php' => 'BaseRealityEvent.js',
//	'StaticTest.php' => 'StaticTest.js',
//	'Content.php' => 'Content.js',
//	'ContentImage.php' => 'ContentImage.js',
//	'PublicPrivate.php' => 'PublicPrivate.js',
);


foreach($filesToConvert as $inputFilename => $outputFilename){
	$phpToJavascript = new PHPToJavascript\PHPToJavascript($inputFilename);
	//$jsOutput = $phpToJavascript->toJavascript();
	$phpToJavascript->generateFile($outputFilename, $inputFilename);
}




?>