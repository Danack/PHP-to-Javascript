<?php

namespace PHPToJavascript;

class CodeConverterState_importNamespace extends CodeConverterState{

	public function		enterState($extraParams = array()){
		$this->stateMachine->addJS('/*');
	}

	function	processToken($name, $value, $parsedToken){
		if($name == ';'){
			$this->stateMachine->addJS('*/');
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return;
		}

		$this->stateMachine->addJS($value);
		//parse the namespace
	}
}



?>