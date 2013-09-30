<?php

namespace PHPToJavascript;

class CodeConverterState_TSTRING extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		$value = convertPHPValueToJSValue($value);

        if($this->stateMachine->isDefined($value)) {
            $defineValue = $this->stateMachine->getDefine($value);
            $this->stateMachine->addJS($defineValue);
		}
		//TODO add isClass($value)
		else if(strcmp('static', $value) == 0 ||
			strcmp('self', $value) == 0){
			$this->stateMachine->addJS($this->stateMachine->getClassName());
		}
		else if($this->stateMachine->currentScope instanceof FunctionParameterScope){
            //Probably a typehint.
			$this->stateMachine->addJS( "/*". $value ."*/");
		}
		else if($this->stateMachine->currentScope instanceof CatchScope){
			//$this->stateMachine->currentScope->addExceptionName($value);
			$this->stateMachine->addJS( "/*". $value ."*/");
		}
		else{
            $variable = $this->stateMachine->getVariableFromScope($value, CODE_SCOPE_CLASS);

            if ($variable) {
                if ($variable->flags & DECLARATION_TYPE_PRIVATE) {
                    if ($this->stateMachine->previousTokensMatch(['this', '.']) == true) {
                        //For the record, this is the hackiest bit of code, so far.
                        $this->stateMachine->deleteTokens(2);
                    }
                }
            }

            $this->stateMachine->addJS($value);
		}

		//TODO - added this to fix "SomeClass::someFunc()" leaving variableFlags in non zero state
		//But not sure if this is safe.
		$this->stateMachine->variableFlags = 0;

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}



