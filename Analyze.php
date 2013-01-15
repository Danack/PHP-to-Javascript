<?php

if(defined('NL') == FALSE){
	define('NL', "\r\n");
}


require_once('CodeTokenizer.php');




class CodeAnalysis{

	var	$public;

	/** @var string */
	var $srcFilename;



	/** @var string */
	var $className;

	/** @var array[string] */
	var $fileLines;

	/** @var ReflectionClass */
	var $class;

	/** @var ReflectionProperty[] */
	var $properties;

	/** @var array */
	var $defaultProperties;

	/** @var ReflectionMethod[] */
	var $methods;

	/** @var ReflectionMethod */
	var $constructorMethod = NULL;

	var $fixups = array();


	function	__construct($srcFilename, $className){

		$this->srcFilename = $srcFilename;
		$this->className = $className;

		$this->fileLines = file($this->srcFilename);

		$this->analyzeCode();
	}

	function	toJavascript(){

		$output = "";

		$output .= $this->getClassJavascript();

		$output .= NL;

		$output .= $this->getMethodsJavascript();

		$output .= NL;

		$output .= $this->getCloseJavascript();

		foreach($this->fixups as $search => $replace){
			$output = str_replace($search, $replace, $output);
		}

		return $output;
	}


	function getClassJavascript(){
		$javascript = "";

		$javascript .= "function ".$this->className."(";

		if($this->constructorMethod != NULL){
			$javascript .= $this->getMethodParameters($this->constructorMethod);
		}

		$javascript .= ") {".NL;

		$javascript .= $this->getPropertiesJavascript();

		if($this->constructorMethod != NULL){
			//$javascript .= $this->getMethodParameters($this->constructorMethod);
			$javascript .= $this->getMethodBody($this->constructorMethod);
		}

		return $javascript;
	}

	function	getPropertiesJavascript(){

		$javascript = NL;

		foreach($this->properties as $property){

			$javascript .= "\t";

			if($property->isPrivate() == TRUE){
				$javascript .= "var ".$property->getName();
				$this->fixups["this.".$property->getName()] = $property->getName();
			}
			else{
				$javascript .= "this.".$property->getName();
			}

			$javascript .= " = ";

			if($this->defaultProperties[$property->getName()] === NULL){
				$javascript .= "null";
			}
			else if($this->defaultProperties[$property->getName()] === FALSE){
				$javascript .= "false";
			}
			else if($this->defaultProperties[$property->getName()] === TRUE){
				$javascript .= "true";
			}
			else{
				$javascript .= $this->defaultProperties[$property->getName()];
			}

			$javascript .= ";".NL;

			//var that = this;
		}


		return $javascript;
	}

	function	getMethodsJavascript(){
		$javascript = "";

		foreach($this->methods as $method){
			$javascript .= $this->getMethod($method);

			$javascript .= NL;
			$javascript .= NL;
		}

		return $javascript;
	}


	function getMethod(ReflectionMethod $method){

		$javascript = "";

		$javascript .= "\t"."this.".$method->getName()." = function(";
			//Get parameters
		$javascript .= $this->getMethodParameters($method);
		$javascript .= "){".NL;

		$javascript .= $this->getMethodBody($method);

		$javascript .= "\t"."}".NL;

		return $javascript;
	}

	function getMethodBody($method){
		$startLine = $method->getStartLine() - 1; // it's actually - 1, otherwise you wont get the function() block
		$endLine = $method->getEndLine();

		$javascript = $this->generateJavascriptFromFile($startLine, $endLine);

		return $javascript;
	}


	/**
	 * @param $method ReflectionMethod
	 */
	function	getMethodParameters($method){

		$javascript = "";

		$commaString = "";

		foreach($method->getParameters() as $parameter){
			$javascript .= $commaString;
			$javascript .= $parameter->getName();

			$commaString = ", ";
		}

		return $javascript;
	}

	function	getCloseJavascript(){

		return "}".NL;
	}


	function	generateJavascriptFromFile($startLine, $endLine){


		$code = "<?php ".NL;

		//+1 and -1 as we skip the outside {} for the function.
		for($x=$startLine + 1 ; $x<$endLine - 1 ; $x++){
			$code .= $this->fileLines[$x].NL;
		}

		$code .= "?>";


		$codeTokenizer = new CodeTokenizer($code);

		return $codeTokenizer->toJavascript();
	}


	function	analyzeCode(){
		$this->class = new ReflectionClass($this->className);

		$this->properties = $this->class->getProperties();
		$this->methods = $this->class->getMethods();
		$this->defaultProperties = $this->class->getDefaultProperties();

		$this->analyzeClass();
		$this->analyzeProperties();
		$this->analyzeMethods();
	}

	function	analyzeClass(){

		foreach($this->methods as $key => $method){

			if($method->getName() == '__construct' ||
				$method->getName() == $this->className ){
				$this->constructorMethod = $method;

				unset($this->methods[$key]);
				break;
			}
		}
	}

	function	analyzeProperties(){
	}


	function	analyzeMethods(){

//		foreach($this->methods as $method){
//
//			$startLine = $method->getStartLine() - 1; // it's actually - 1, otherwise you wont get the function() block
//			$endLine = $method->getEndLine();
//
//			$name = $method->getName();
//
//			echo "Generate function $name from lines $startLine to $endLine.\r\n";
//
//			for($x=$startLine ; $x<$endLine ; $x++){
//			echo trim($this->fileLines[$x]);
//			echo "\n";
//			}
//				echo "\r\n";
//			}
	}

}




?>