<?php

namespace PHPToJavascript;


class CodeConverterState_TOBJECTOPERATOR extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){

		if($name == 'T_VARIABLE'){
			echo "Analyze ".$value."\n";

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
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return;
		}
		echo "Interesting - name $name value = $value\n";

	}
}



?>