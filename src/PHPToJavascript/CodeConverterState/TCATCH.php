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
//		else if($name == ')'){
//			$this->stateMachine->addJS(')');
//			$this->changeToState(CONVERTER_STATE_DEFAULT);
//		}
//		else if($name == '('){
//			$this->stateMachine->addJS('(');
//		}
//		else if ($name == 'T_STRING'){
//			//Throw away exception class
//		}
//		else if($name == 'T_VARIABLE'){
//			$varName = cVar($value);
//
//			//Variable is implicitly declared - doesn't need a `var` in front of it.
//			$this->stateMachine->currentScope->addScopedVariable($value, 0);
//
//			$this->stateMachine->addJS($varName);
//		}
	}
}





?>