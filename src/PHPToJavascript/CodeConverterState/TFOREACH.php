<?php

namespace PHPToJavascript;

class CodeConverterState_TFOREACH extends CodeConverterState {

	var $chunkArray = array();

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->chunkArray = '';
	}

	//till the {
	function	processToken($name, $value, $parsedToken){

		if ($name == 'T_VARIABLE'){
			$this->chunkArray[] = cVar($value);
		}

		if ($name == '{') {
			if (count($this->chunkArray) == 2) {
				$array = $this->chunkArray[0];
				$val = $this->chunkArray[1];
				$this->stateMachine->addJS( "for (var {$val}Val in $array) {".
					"		\n                        $val = $array"."[{$val}Val];");
			}
			if (count($this->chunkArray) == 3) {
				$array = $this->chunkArray[0];
				$key = $this->chunkArray[1];
				$val = $this->chunkArray[2];
				$this->stateMachine->addJS("for (var $key in $array) {".
					"\n                        $val = $array"."[$key];");
			}
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}
}




?>