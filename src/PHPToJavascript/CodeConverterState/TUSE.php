<?php

namespace PHPToJavascript;

class CodeConverterState_TUSE extends CodeConverterState{

	var $extendsName = null;

	public function		enterState($extraParams = array()){
		$this->extendsName = null;
	}

	function	processToken($name, $value, $parsedToken){

		if($this->stateMachine->currentScope instanceof GlobalScope){
			$this->changeToState(CONVERTER_STATE_IMPORT_NAMESPACE);
			return true;
		}
		else if($this->stateMachine->currentScope instanceof ClassScope){
			//$this->changeToState(CONVERTER_STATE_T_EXTENDS);

			if($name == 'T_STRING'){
				//echo "Need to grab variables/functions from [$value]";
				$this->extendsName = $value;
			}
			if($name == ';'){
				if($this->extendsName == null){
					throw new \Exception("Didn't find class name to USE.");
				}

				$this->stateMachine->currentScope->addParent($this->extendsName);
				$this->changeToState(CONVERTER_STATE_DEFAULT);
				return;//don't need to include the ';'
			}
		}
		else{
			throw new \Exception("use is only expected in Global and class scopes. Don't know what to do with it here.");
		}


	}
}





?>