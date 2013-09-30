<?php

namespace PHPToJavascript;

class CodeConverterState_define extends CodeConverterState {

	var $defineName;
    
    private $pastDefineToken = false;

	public function		enterState($extraParams = array()) {
		parent::enterState($extraParams);
		$this->defineName = FALSE;
		$this->stateMachine->addJS("// ");
        $this->pastDefineToken = false;
	}

	function	processToken($name, $value, $parsedToken) {

		$this->stateMachine->addJS($parsedToken); //Maybe should be parsedToken

		if($name == 'T_CONSTANT_ENCAPSED_STRING') {
			if($this->defineName == FALSE){
                $this->defineName = unencapseString($value);
			}
			else{
                $this->stateMachine->addDefine($this->defineName, unencapseString($value));
				$this->changeToState(CONVERTER_STATE_DEFAULT);
			}
        }
        else if($name == 'T_LNUMBER') {
            $this->stateMachine->addDefine($this->defineName, intval($value, 0));
            $this->changeToState(CONVERTER_STATE_DEFAULT);
        }
        else if($name == 'T_DNUMBER') {
            $this->stateMachine->addDefine($this->defineName, floatval($value));
            $this->changeToState(CONVERTER_STATE_DEFAULT);
        }
        else if($name == 'T_STRING') {
            $this->stateMachine->addDefine($this->defineName, convertPHPValueToJSValue($value));
            if ($this->pastDefineToken == true) {
                $this->changeToState(CONVERTER_STATE_DEFAULT);
            }
        }

        $this->pastDefineToken = true;
	}
}


