<?php

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