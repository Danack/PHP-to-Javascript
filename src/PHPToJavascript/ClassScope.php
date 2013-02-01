<?php

namespace PHPToJavascript;

class ClassScope extends CodeScope{

	var		$methodsStartIndex = FALSE;

	function getType(){
		return CODE_SCOPE_CLASS;
	}


	function	getJS(){
		$jsRaw = $this->getJSRaw();

		$constructor = FALSE;

		foreach($this->jsElements as $jsElement){
			if($jsElement instanceof CodeScope){
				if($jsElement->getName() == '__construct'){
					$constructor = $jsElement->getJSRaw();
					break;
				}
			}
		}

		if($constructor !== FALSE){
			$constructorInfo = trimConstructor($constructor);
			$jsRaw = str_replace(CONSTRUCTOR_PARAMETERS_POSITION, $constructorInfo['parameters'], $jsRaw);
			$jsRaw = str_replace(CONSTRUCTOR_POSITION_MARKER, $constructorInfo['body'], $jsRaw);
		}

		return $jsRaw;
	}

	function	markMethodsStart(){

		//echo "WUT - ".$this->methodsStartIndex."\n";

		if($this->methodsStartIndex === FALSE){
			$this->methodsStartIndex = count($this->jsElements);
			//	echo "really";


			$this->addJS(CONSTRUCTOR_POSITION_MARKER);
		}
	}

	function	getScopedVariableForScope($variableName, $isClassVariable){

		$cVar = cvar($variableName);

		if(array_key_exists($cVar, $this->scopedVariables) == TRUE){
			$variableFlag = $this->scopedVariables[$cVar];

			if($isClassVariable == TRUE){
				if($variableFlag & DECLARATION_TYPE_PRIVATE){
					return 	$variableName;
				}
				if($variableFlag & DECLARATION_TYPE_STATIC){
					return 	$variableName;
				}
				if($variableFlag & DECLARATION_TYPE_PUBLIC){
					return 	'this.'.$variableName;
				}
			}
		}

		if($isClassVariable == TRUE){
			//Either a function or property set below where it is defined.
			// OR it could be a variable that is defined in the parent class' scope.
			return 	'this.'.$variableName;
		}

		return NULL;
	}
}




?>