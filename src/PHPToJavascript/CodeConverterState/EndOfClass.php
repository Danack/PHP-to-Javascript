<?php

namespace PHPToJavascript;

class CodeConverterState_EndOfClass extends CodeConverterState{

	var $previousScope = NULL;

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->previousScope = $extraParams['previousScope'];
	}

	function	processToken($name, $value, $parsedToken){
		if($name == '}'){
			$this->stateMachine->addJS('}'."\n\n");
			$className = $this->previousScope->name;
//			$this->stateMachine->addJS("$className = new $className(/*Constuctor for static methods+vars*/);"."\n\n");
		}
//		else{
//			throw new Exception( "Only token } should be getting here.");
//		}

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



?>