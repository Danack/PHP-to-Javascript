<?php

namespace PHPToJavascript;

class CodeConverterState_TEXTENDS  extends CodeConverterState{



	function	processToken($name, $value, $parsedToken){
		if($name == 'T_STRING'){
			//echo "Need to grab variables/functions from [$value]";
			$this->stateMachine->currentScope->addParent($value);
		}

		if($name == '{'){
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return TRUE;
		}

		// This would support use
		/*if($name == ';'){
			if($this->extendsName == null){
				throw new \Exception("Didn't find class name to USE.");
			}

			$this->stateMachine->currentScope->addParent($this->extendsName);
			//$this->extendsName = $value;
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return;//don't need to include the ';'
		}*/
	}
}




?>