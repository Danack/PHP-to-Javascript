<?php

namespace PHPToJavascript;

class ClassScope extends CodeScope{

	var		$methodsStartIndex = false;

	var 	$publicVariables = array();
	var		$staticVariables = array();

	var 	$parentClasses = array();

	var 	$currentVariableName = null;
	var 	$currentVariableForConcattingValue = null;

	function addParent($value){
		$this->parentClasses[] = $value;
	}


	function getType(){
		return CODE_SCOPE_CLASS;
	}

	function  getEndOfScopeJS(){
		$js = "";
		$js .= "\n";
		$js .= $this->getClassInheritanceJS();
		$js .= "\n";
		$js .= $this->getClassVariableInitJS();
		return $js;
	}

	function getClassInheritanceJS(){
		$js = "";

		if(count($this->parentClasses) > 0){
			$js .= "\n\n";

			foreach($this->parentClasses as $parentClass){
				$childClass = $this->name;
				//Inheritance pattern taken from
				//https://developer.mozilla.org/en-US/docs/JavaScript/Introduction_to_Object-Oriented_JavaScript

				$js .= "// inherit $parentClass\n";
				$js .= "$childClass.prototype = new $parentClass();\n";

				$js .= "// correct the constructor pointer because it points to $parentClass\n";
				$js .= "$childClass.prototype.constructor = $childClass;\n";

				$js .= "//Need to copy the static functions across and replace the parent class name with the child class name.\n";

				$js .= "$.extend($childClass, $parentClass);\n";
			}

			$js .= "\n";
		}

		return $js;
	}


	function	getClassVariableInitJS(){
		$js = "";

		foreach($this->publicVariables as $name => $value){
			if($value === false){
				$value = 'null';
			}

			$js .= $this->name.".prototype.".$name." = $value;\n";
		}

		foreach($this->staticVariables as $name => $value){
			if($value === false){
				$value = 'null';
			}

			$js .= $this->name.".".$name." = $value;\n";
		}

		return $js;
	}

	function getJSForClassInPlace(){

		$js = "";

		foreach($this->jsElements as $jsElement){
			if($jsElement instanceof CodeScope){
				$js .= $jsElement->getInPlaceJS();
			}
			else if(is_string($jsElement)){
				$js .= $jsElement;
			}
			else{
				throw new \Exception("Unknown type in this->jsElements of type [".get_class($jsElement)."]");
			}
		}
		return $js;
	}

	function	getJS(){

		$js = "//Start class here \n";

		$js = "";
		$js .= $this->getJSForClassInPlace();
		$js .= "\n";
		$js .= $this->getEndOfScopeJS();
		$js .= "\n";
		$js .= $this->getChildDelayedJS();

		$js = $this->replaceConstructorInJS($js);

		$js = $this->manglePrivateFunctions($js);

		return $js;
	}

	function	getChildDelayedJS(){
		$js = "";

		foreach($this->jsElements as $jsElement){
			if($jsElement instanceof CodeScope){
				$js .= $jsElement->getDelayedJS($this->getName());
				$js .= "\n";
			}
		}

		return $js;
	}


	/**
	 * Change function calls to private functions from "this.privateFunction()" to
	 * "privateFunction()" as that is how they need to be called in Javascript OO layout.
	 *
	 * @param $js
	 * @return mixed
	 */
	function manglePrivateFunctions($js){

		$search = array();
		$replace = array();

		foreach($this->jsElements as $jsElement){
			if($jsElement instanceof FunctionParameterScope){

				/** @var $functionParameterScope FunctionParameterScope  */
				$functionParameterScope = $jsElement;

				if(($functionParameterScope->variableFlag & DECLARATION_TYPE_PRIVATE) != 0){
					//echo "You touched my privates ".$functionParameterScope->getName();
					$search[] = "this.".$functionParameterScope->getName()."(";
					$replace[] = "".$functionParameterScope->getName()."(";
				}
			}
		}

		return str_replace($search, $replace, $js);
	}

	/**
	 * The constructor method for a 'class' in Javascript is actually just inlined with the class scope,
	 * rather than being a function inside the class. This function moves the constructor code from the function
	 * to the class scope, so that it's in the correct place.
	 * @param $js
	 * @return mixed
	 */
	function replaceConstructorInJS($js){
		$constructor = false;

		foreach($this->jsElements as $jsElement){
			if($jsElement instanceof CodeScope){
				if($jsElement->getName() == '__construct'){
					$constructor = $jsElement->getJS();
					break;
				}
			}
		}

		$parentConstructor = "";

		foreach($this->parentClasses as $parentClass){
			$parentConstructor .= "".$parentClass.".call(this);\n";
		}

		if($constructor !== false){
			$constructorInfo = trimConstructor($constructor);
			$constructorInfo['body'] = $parentConstructor.$constructorInfo['body'];
			$js = str_replace(CONSTRUCTOR_PARAMETERS_POSITION, $constructorInfo['parameters'], $js);
			$js = str_replace(CONSTRUCTOR_POSITION_MARKER, $constructorInfo['body'], $js);
		}
		else{
			//There is no constructor - just remove the magic strings
			$js = str_replace(CONSTRUCTOR_PARAMETERS_POSITION, '', $js);
			$js = str_replace(CONSTRUCTOR_POSITION_MARKER, $parentConstructor, $js);
		}

		return $js;
	}


	/**
	 * Mark where methods start, so we can put the class constructor here.
	 */
	function	markMethodsStart(){
		if($this->methodsStartIndex === false){
			$this->methodsStartIndex = count($this->jsElements);
			$this->addJS(CONSTRUCTOR_POSITION_MARKER);
		}
	}

	function	getScopedVariableForScope($variableName, $isClassVariable){
		$cVar = cvar($variableName);

		if(array_key_exists($cVar, $this->scopedVariables) == true){
			$variableFlag = $this->scopedVariables[$cVar];

			if($isClassVariable == true){
				if($variableFlag & DECLARATION_TYPE_PRIVATE){
					return 	$variableName;
				}
				if($variableFlag & DECLARATION_TYPE_STATIC){
					return 	$variableName;
				}
				if($variableFlag & DECLARATION_TYPE_PUBLIC){
					//TODO should the 'this' be here, or in the T_OBJECTOPERATOR
					return 	$variableName;
				}
			}
		}

		if($isClassVariable == true){
			//Either a function or property set below where it is defined.
			// OR it could be a variable that is defined in the parent class' scope.
			return 	'this.'.$variableName;
		}

		return null;
	}

	function addStaticVariable($variableName){
		$this->staticVariables[$variableName] = false;
		$this->currentVariableForConcattingValue = &$this->staticVariables[$variableName];

		$this->currentVariableName = $variableName;
	}

	function addPublicVariable($variableName){
		$this->publicVariables[$variableName] = false;
		$this->currentVariableForConcattingValue = &$this->publicVariables[$variableName];
		$this->currentVariableName = $variableName;
	}

	function setVariableString($variableName, $string) {
		if (array_key_exists($variableName, $this->staticVariables) == true) {
			$this->staticVariables[$variableName] = $string;
			return;
		}

		if (array_key_exists($variableName, $this->publicVariables) == true) {
			$this->publicVariables[$variableName] = $string;
			return;
		}

		throw new \Exception("Variable [$variableName] not known - cannot set it's string value.");
	}



	/**
	 * For class variables that are added to the class scope, but are delayed to be declared outside
	 * the function (to be public or static) we need to grab the default values to be able to set
	 * the variables to them. Incidentally grabs any comments.
	 *
	 * @param $value
	 * @throws \Exception
	 */
	function addToVariableValue($value){
		if($this->currentVariableForConcattingValue === null){
			throw new \Exception("Trying to concat [$value] to the current variable - but it's not set. ");
		}

		if($this->currentVariableForConcattingValue === false){
			$this->currentVariableForConcattingValue = '';
		}

		$this->currentVariableForConcattingValue .= $value;
	}

	function	getDelayedJS($parentScopeName){
		$output = "";

		foreach($this->publicVariables as $name => $value){
			if($value === false){
				$value = 'null';
			}
			$output .= $this->name.".prototype.".$name." = $value;\n";
		}

		foreach($this->staticVariables as $name => $value){
			if($value === false){
				$value = 'null';
			}
			$output .= $this->name.".".$name." = $value;\n";
		}

		return $output;
	}
}




?>