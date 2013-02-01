<?php

namespace PHPToJavascript;

class CodeConverterState_TIMPLEMENTSINTERFACE  extends CodeConverterState{

	public $first = FALSE;

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->first = TRUE;
	}

	function	processToken($name, $value, $parsedToken){
		if($this->first == TRUE){
			$this->first = FALSE;
			$this->stateMachine->addJS("/*");
		}

		if($name == 'T_STRING' || $name == 'T_WHITESPACE'){
			$this->stateMachine->addJS($value);
		}

		if($name == '{'){
			$this->stateMachine->addJS("*/");
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return TRUE;
		}
	}
}



?>