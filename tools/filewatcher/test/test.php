<?php
$s = stat(__FILE__);
print_r($s['dev']);
$s = stat("../../../tests/MultiLine.php");
print_r($s);
$s = stat("../phptojs.php");
//print_r($s['dev']);
$s = stat("../../../tests/ArrayExample.php");
print_r($s);
exit;
error_reporting(E_ALL);
require_once("../SplClassLoader.php");
$loader = new SplClassLoader('PHPToJavascript', __DIR__ . '/../../../src');
$loader->register();

$file = __DIR__.'/src/test2.php';
$outputFile = __DIR__.'/js/test2.js';

$phpToJavascript = new PHPToJavascript\PHPToJavascript();
$phpToJavascript->setEchoConversionFunction(PHPToJavascript\PHPToJavascript::$ECHO_TO_CONSOLE_LOG);
$phpToJavascript->addFromFile($file);
$phpToJavascript->generateFile($outputFile, $file);