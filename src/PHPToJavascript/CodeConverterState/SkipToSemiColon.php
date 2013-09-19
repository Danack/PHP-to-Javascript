<?php

namespace PHPToJavascript;

class CodeConverterState_SkipToSemiColon extends CodeConverterState {

    function    processToken($name, $value, $parsedToken) {        
        if ($name == ";") {
            $this->changeToState(CONVERTER_STATE_DEFAULT);
        }
    }
}
