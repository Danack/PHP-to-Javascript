<?php

namespace PHPToJavascript;

class CodeConverterState_TGLOBAL extends CodeConverterState {

    function    processToken($name, $value, $parsedToken) {
        $this->changeToState(CONVERTER_STATE_SKIP_TO_SEMICOLON);
    }
}