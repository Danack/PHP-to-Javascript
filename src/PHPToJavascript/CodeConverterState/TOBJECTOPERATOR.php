<?php

namespace PHPToJavascript;


class CodeConverterState_TOBJECTOPERATOR extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		if($name == 'T_VARIABLE'){
			if(strpos($value, '$') !== FALSE){
				$this->stateMachine->addJS("[");
				$this->stateMachine->addSymbolAfterNextToken(']');
			}
			else{
				$this->stateMachine->addJS(".");
			}

			$this->changeToState(CONVERTER_STATE_VARIABLE);
			return TRUE;
		}


		if($name == "T_STRING"){

			$this->stateMachine->addJS(".");

			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return TRUE;
		}

		//echo "Interesting - name $name value = $value\n";
	}
}



?>