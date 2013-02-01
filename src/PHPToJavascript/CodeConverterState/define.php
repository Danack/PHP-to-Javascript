<?php

namespace PHPToJavascript;

class CodeConverterState_define extends CodeConverterState{

	var $defineName;

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->defineName = FALSE;
		$this->stateMachine->addJS("// ");
	}

	function	processToken($name, $value, $parsedToken){

		$this->stateMachine->addJS($parsedToken); //Maybe should be parsedToken

		if($name == 'T_CONSTANT_ENCAPSED_STRING'){
			if($this->defineName == FALSE){
				$this->defineName = $value;
			}
			else{
				$this->stateMachine->addDefine($this->defineName, $value);
				$this->changeToState(CONVERTER_STATE_DEFAULT);
			}
		}
	}
}





?>