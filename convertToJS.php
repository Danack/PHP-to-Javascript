<?php

require_once("Analyze.php");
require_once("functions.php");

$exportPath = "export";


$filesToConvert = array(
	//'Code' => FALSE,
	"Content" => FALSE, //'Content',
	//"Picture" => 'Picture',
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