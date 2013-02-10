<?php

namespace PHPToJavascript;

class CodeConverterState_Echo extends CodeConverterState {

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
	}

	function	processToken($name, $value, $parsedToken){
		//$this->stateMachine->addJS('document.write('.$parsedToken);
		$this->stateMachine->addJS('alert('.$parsedToken);
		$this->stateMachine->setPendingSymbol(';', ")");
		$this->changeToState(CONVERTER_STATE_DEFAULT);
		return FALSE;
	}
}



?>