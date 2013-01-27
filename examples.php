<?php

require_once("PHPToJavascript.php");

$exportPath = "export";

error_reporting(E_ALL);
//sleep(1);

$filesToConvert = array(

	//'src/ClassVariables.php' => 'export/ClassVariables.js',
	//'src/Code.php' => 'export/Code.js',
	//"src/Content.php",
	//'src/StaticTest.php' => 'export/StaticTest.js',
	//'src/Content.php' => 'export/Content.js',
	'src/ContentImage.php' => 'export/ContentImage.js',
);

try{
	foreach($filesToConvert as $inputFilename => $outputFilename){
		$phpToJavascript = new PHPToJavascript($inputFilename);
		$jsOutput = $phpToJavascript->toJavascript();
		generateFile($outputFilename, $inputFilename, $jsOutput);
	}
}
catch(Exception $e){
	echo "Exception caught: $e";
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
			//echo "Had to create directory $segment";
			$directoryCreated = mkdir($segment);

			if ($directoryCreated == FALSE) {
				throw new Exception("Failed to create directory $filePath");
			}
		}
	}
}




?>