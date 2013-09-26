<?php


namespace PHPToJavascript;



class CodeConverterState_CapturingDefaultValue extends CodeConverterState {

    function	processToken($name, $value, $parsedToken){
        if($name == ')' || $name == ','){
            //Finished capturing.
            $this->stateMachine->changeToState(CONVERTER_STATE_DEFAULT);
            return true;
        }
        else{
            
            if ($name == 'T_STRING' ||
                $name == 'T_CONSTANT_ENCAPSED_STRING') {
                $this->stateMachine->currentScope->addToJsForPreviousVariable($value);
            }
            else {
                $this->stateMachine->currentScope->addToJsForPreviousVariable($parsedToken);
            }
        }
    }
}

 