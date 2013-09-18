<?php
error_reporting(E_ALL);
require_once("SplClassLoader.php");
$loader = new SplClassLoader('PHPToJavascript', __DIR__ . '/../src');
$loader->register();

$file = __DIR__.'/test/src/test2.php';
$outputFile = __DIR__.'/test/js/test2.js';

$phpToJavascript = new PHPToJavascript\PHPToJavascript();
$phpToJavascript->setEchoConversionFunction(PHPToJavascript\PHPToJavascript::$ECHO_TO_CONSOLE_LOG);
$phpToJavascript->addFromFile($file);
$phpToJavascript->generateFile($outputFile, $file);