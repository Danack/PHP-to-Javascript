<?php




namespace PHPToJavascript;

class CodeConverterState_TCATCH  extends CodeConverterState{

//	function enterState($extraParams = array()){
//
//
//	}


	function	processToken($name, $value, $parsedToken){
		if($name == 'T_CATCH'){
			$this->stateMachine->addJS('catch');
			$this->stateMachine->pushScope(
				CODE_SCOPE_CATCH,
				'catch'
			);
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}





?>