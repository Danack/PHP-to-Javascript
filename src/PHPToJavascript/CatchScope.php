<?php

namespace PHPToJavascript;


class CatchScope extends CodeScope{

	var $exceptionNames = array();

	/**
	 * @param $variableName
	 * @param $variableFlags 
	 * @return mixed
	 */
	function	getScopedVariableForScope($variableName, $variableFlags){
		$cVar = cvar($variableName);

		if(array_key_exists($cVar, $this->scopedVariables) == TRUE){
			//$variableFlag = $this->scopedVariables[$cVar];
			if($variableFlags & DECLARATION_TYPE_STATIC){
				return 	$this->name.".".$variableName;
			}
			else if(($variableFlags & DECLARATION_TYPE_CLASS)){
				if(strpos($variableName, "$") !== FALSE){
					//it's a variable variable like "this->$var";
					return 	'this['.$variableName.']';
				}
				else{
					return 	'this.'.$variableName;
				}
			}

			return $variableName;
		}

		return NULL;
	}

	function getType() {
		return CODE_SCOPE_CATCH;
	}

	function	getJS(){

		$js = "";
		$jsRaw = parent::getJS();

		$search = array();
		$replace = array();

		foreach($this->exceptionNames as $exceptionName){
			$search[] = "".$exceptionName.".getMessage()";
			$replace[] = "".$exceptionName.".message";
		}

		$jsRaw = str_replace($search, $replace, $jsRaw);
		$js .= $jsRaw;
		return $js;
	}


	function	addExceptionName($value){
		$this->exceptionNames[] = $value;
	}
}

