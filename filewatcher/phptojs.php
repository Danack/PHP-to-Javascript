<?php
error_reporting(E_ALL);
$file = realpath(getcwd() . DIRECTORY_SEPARATOR . $argv[1]);
if (!$file) {
	echo "error: file not found";
}
$outputFile = str_replace('.php', '.js', $file);
if (count($argv) == 4) {
	$tmp  = explode(DIRECTORY_SEPARATOR, str_replace($argv[2], '', getcwd()));
	$path = $argv[3];
	foreach ($tmp as $dir) {
		if ($dir == '') {
			continue;
		}
		$path .= DIRECTORY_SEPARATOR . $dir;
		if (!file_exists($path)) {
			if (!mkdir($path)) {
				echo "cant create directory '" . $path . "'" . PHP_EOL;
				exit(0);
			}
		}
	}
	$outputFile = str_replace($argv[2], $argv[3], $outputFile);
}
require_once("SplClassLoader.php");
$loader = new SplClassLoader('PHPToJavascript', __DIR__ . '/../src');
$loader->register();
echo $file . " -> " . $outputFile . PHP_EOL;
$phpToJavascript = new PHPToJavascript\PHPToJavascript();
$phpToJavascript->setEchoConversionFunction(PHPToJavascript\PHPToJavascript::$ECHO_TO_CONSOLE_LOG);
$phpToJavascript->addFromFile($file);
$phpToJavascript->generateFile($outputFile, $file);

