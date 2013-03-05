<?php


namespace PHPToJavascript;


class CodeConverterState_TNAMESPACE extends CodeConverterState{

	public function		enterState($extraParams = array()){
		$this->stateMachine->addJS('/*');
	}

	function	processToken($name, $value, $parsedToken){
		//$this->stateMachine->addJS('.');
		//$this->stateMachine->addVariableFlags(DECLARATION_TYPE_CLASS);

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