<?php

require_once("SplClassLoader.php");

$loader = new SplClassLoader('PHPToJavascript', '../src');
$loader->register();

$exportPath = "export";

error_reporting(E_ALL);

$filesToConvert = array(
	'ArrayExample.js' => 'ArrayExample.php',
	'AssigningThis.js' => 'AssigningThis.php',
	'ClassExample.js' => 'ClassExample.php',
	'CustomEvent.js' => 'CustomEvent.php' ,
	'DefaultValue.js' => 'DefaultValue.php',
	'Inheritance.js' => 'Inheritance.php',

	'NameSpace.js' => 'NameSpace.php',
	//Broken test
	//'PublicPrivate.js' => 'PublicPrivate.php',
	'SimpleExample.js' => 'SimpleExample.php',

	'StaticTest.js' => 'StaticTest.php',

	'SwitchStatement.js' => 'SwitchStatement.php',
	'TryCatch.js' => 'TryCatch.php',
	'TraitExample.js' => array(
		'TraitInclude.php',
		'TraitExample.php',
	),
	'TypeHinting.js' => 'TypeHinting.php',
);

//$filesToConvert = array(
//	'ClassSetVarExample.js' => 'ClassSetVarExample.php',
//	'ArrayExample.js' => 'ArrayExample.php',
//);


$convertedFiles = array();

function generateTestPage($convertedFiles){

	$fileHandle = fopen("output/test.html",  "w");

	fwrite($fileHandle, "<html><body>Tests are loaded via javascript into this webpage. <br/>&nbsp;<br/> If nothing turns green then probably the conversion failed completely, and either the Javascript files are not present or so invalid that the can't be compiled. <br/>&nbsp;<br/>");

	foreach($convertedFiles as $convertedFile){
		$testID = str_replace(".", "_", $convertedFile);
		$testIDStatus = $testID."_status";
		fwrite($fileHandle, "<div>$convertedFile -
			<span id='$testID'>Not tested.</span>
			<span id='$testIDStatus'>
				<span style='background-color: #af3f3f;'>Incomplete.</span>
			</span>
			</div>");
	}

	fwrite($fileHandle, "</body>");

	fwrite($fileHandle, "<script type='text/javascript' src='../php.js'></script>");
	fwrite($fileHandle, "<script type='text/javascript' src='../testStart.js'></script>");
	fwrite($fileHandle, "<script type='text/javascript' src='../jquery-1.9.1.min.js'></script>");

	foreach($convertedFiles as $convertedFile){
		$testID = str_replace(".", "_", $convertedFile);
		fwrite($fileHandle, "<script type='text/javascript'> \n");

			fwrite($fileHandle, "testStart('".$testID."_status');\n");
		fwrite($fileHandle, "</script>\n");

		fwrite($fileHandle, "<script type='text/javascript' src='".$convertedFile."'></script>\n");
		$testID = str_replace(".", "_", $convertedFile);
		fwrite($fileHandle, "<script type='text/javascript'> \n");
		fwrite($fileHandle, "setTestsResult('$testID');\n");
		fwrite($fileHandle, "</script>\n");
	}

	fwrite($fileHandle, "</html>");
	fclose($fileHandle);
}

foreach($filesToConvert as $outputFilename =>  $inputFileList ){
	$phpToJavascript = new PHPToJavascript\PHPToJavascript();

	//$phpToJavascript->setTrace(true);

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

	$phpToJavascript->generateFile("output/".$outputFilename, $inputFilename);

	$convertedFiles[] = $outputFilename;
}


generateTestPage($convertedFiles);


function testEnd(){

}

?>