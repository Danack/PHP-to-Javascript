<?php

namespace PHPToJavascript;



class CodeConverterState_ARRAY extends CodeConverterState {

	private  		$arraySymbolRemap = array('('=>'{',	')'=>'}',);
	//var				$stateChunk = '';

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		//$this->stateChunk = '';
	}

	function	processToken($name, $value, $parsedToken){

		$this->stateMachine->pushScope(CODE_SCOPE_ARRAY, $value);

		//$this->stateMachine->addJS(ARRAY_MARKER_START_MAGIC_STRING);
		$this->changeToState(CONVERTER_STATE_DEFAULT);

		/*
		if($name == ')'){//This code needs refactoring - as $_keep is not safe, as parens are stateful.
			$parsedToken = ')';
		}

		if(array_key_exists($parsedToken, $this->arraySymbolRemap) == TRUE){
			$parsedToken = $this->arraySymbolRemap[$parsedToken];//change name to other value
		}

		//TODO - this needs to go through the scope for a variable name
		$parsedToken = str_replace("$", "", $parsedToken);

		if($name == "T_CONSTANT_ENCAPSED_STRING"){
			$this->stateChunk .= $value;
		}
		else{
			$this->stateChunk .= $parsedToken;
		}

		if($name == ';'){
			$js = $this->stateChunk;

			if (strpos($js, ':') === FALSE) {

				$arrayFunction = function($_matches) {
					$this->tmp++;
					if (strpos($_matches[0], ':') === FALSE) {
						return ($_matches[1].$this->tmp.':'.$_matches[2].$_matches[3].$_matches[4].$_matches[5]);
					} else {
						return $_matches[0];
					}
				};

				$js = preg_replace_callback ('/([{, \t\n])(\'.*\')(|.*:(.*))([,} \t\n])/Uis', $arrayFunction, $js);
			}

			$this->stateMachine->addJS($js);
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		} */
	}
}




?>