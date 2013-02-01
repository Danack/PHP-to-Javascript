<?php

namespace PHPToJavascript;

class CodeConverterState_TABSTRACTREMOVE extends CodeConverterState{

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->first = TRUE;

		$this->stateMachine->addJS("//");
	}

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->addJS('//'.$value);

		if($name == ';'){
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}




?>