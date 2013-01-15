<?php

//require_once("PHP2JS.php");

require_once("Analyze.php");
require_once("functions.php");


$exportPath = "export";

$filesToConvert = array(
	"Content",
);

try{

	foreach($filesToConvert as $fileToConvert){

		$srcFilename = "src/".$fileToConvert.".php";

		require_once($srcFilename);

		$codeAnalysis = new CodeAnalysis($srcFilename, "Content");

		$jsOutput = $codeAnalysis->toJavascript();

		$outputFilename = "export/".$fileToConvert.".js";

		generateFile($outputFilename, $srcFilename, $jsOutput);
	}
}
catch(Exception $e){
	echo "Exception caught: $e";
}


?>