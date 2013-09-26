<?php


namespace PHPToJavascript;



class CodeConverterState_EmbeddedVariable extends CodeConverterState {

    public function		enterState($extraParams = array()){
        $this->stateMachine->addJS('" + ');
    }

    function	processToken($name, $value, $parsedToken){

        if ($name == 'T_STRING_VARNAME'){
            $scopedVariableName = $this->stateMachine->getVariableNameForScope($value, 0);
            $this->stateMachine->addJS($scopedVariableName);
        }

        if ($name == '}') {

            $this->stateMachine->addJS(' + "');
            $this->stateMachine->changeToState(CONVERTER_STATE_DEFAULT);
        }
    }
}

 