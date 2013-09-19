<?php

namespace PHPToJavascript;

class CodeConverterState_TGLOBAL extends CodeConverterState {

    function    processToken($name, $value, $parsedToken) {
        $this->stateMachine->addVariableFlags(DECLARATION_TYPE_GLOBAL);
        $this->changeToState(CONVERTER_STATE_SKIP_TO_SEMICOLON);
    }
}