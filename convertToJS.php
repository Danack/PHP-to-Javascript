<?php

//require_once("PHP2JS.php");

require_once("Analyze.php");
require_once("functions.php");

$exportPath = "export";

$filesToConvert = array(
	"Content" => 'Content',
	'Code' => FALSE,
);


try{

	foreach($filesToConvert as $fileToConvert => $classToExtract){

		$srcFilename = "src/".$fileToConvert.".php";

		$codeAnalysis = new CodeAnalysis($srcFilename, $classToExtract);

		$jsOutput = $codeAnalysis->toJavascript();

		$outputFilename = "export/".$fileToConvert.".js";

		generateFile($outputFilename, $srcFilename, $jsOutput);
	}
}
catch(Exception $e){
	echo "Exception caught: $e";
}


?>