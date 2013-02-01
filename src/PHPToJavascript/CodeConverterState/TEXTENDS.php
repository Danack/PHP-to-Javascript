<?php

namespace PHPToJavascript;

class CodeConverterState_TEXTENDS  extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
		if($name == T_STRING){
			echo "Need to grab variables/functions from [$value]";
		}

		if($name == '{'){
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return TRUE;
		}
	}
}




?>