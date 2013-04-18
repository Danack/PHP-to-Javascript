<?php

namespace PHPToJavascript;

class CodeConverterState_TFOREACH extends CodeConverterState {

	var $arrayElements = array();
	var	$keyOrValueElements = array();
	var	$valueElements = array();

	var $skippedArray = array();

	static public $SUBSTATE_OBJECT			= 'SUBSTATE_OBJECT';
	static public $SUBSTATE_KEY_OR_VALUE	= 'SUBSTATE_FIRST_AS';
	static public $SUBSTATE_VALUE			= 'SUBSTATE_SECOND_AS';

	var	$subState;

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);

		$this->arrayElements = array();
		$this->keyOrValueElements = array();
		$this->valueElements = array();

		$this->subState = CodeConverterState_TFOREACH::$SUBSTATE_OBJECT;
	}

	function	processToken($name, $value, $parsedToken){

		$jsToAdd = FALSE;

		//TODO - this is repeating code elsewhere.
		if ($name == 'T_VARIABLE'){
			$jsToAdd = cVar($value);
		}
		else if ($name == 'T_OBJECT_OPERATOR' ||
				 $name == 'T_DOUBLE_COLON'){
			$jsToAdd = '.';
		}
		else if ($name == 'T_STRING'){
			if (strtolower($value) == 'self') {
				$jsToAdd = $this->stateMachine->getClassName();
			}
			else{
				$jsToAdd = $value;
			}
		}
		else if ($name == 'T_WHITESPACE'){
			$jsToAdd = $value;
		}
		else if($name == ')'){
			//Bracket is added magically, when we write the foreach from the extracted names.
			//$jsToAdd = $name;
		}

		if($name == 'T_AS'){
			$this->subState = CodeConverterState_TFOREACH::$SUBSTATE_KEY_OR_VALUE;
		}

		if($name == 'T_DOUBLE_ARROW'){
			$this->subState = CodeConverterState_TFOREACH::$SUBSTATE_VALUE;
		}

		if($jsToAdd != FALSE){
			switch($this->subState){

				case(CodeConverterState_TFOREACH::$SUBSTATE_OBJECT):{
					$this->arrayElements[] = $jsToAdd;
					break;
				}

				case(CodeConverterState_TFOREACH::$SUBSTATE_KEY_OR_VALUE):{
					$this->keyOrValueElements[] = $jsToAdd;
					break;
				}

				case(CodeConverterState_TFOREACH::$SUBSTATE_VALUE):{
					$this->valueElements[] = $jsToAdd;
					break;
				}
			}
		}

		if ($name == '{') {
			$this->finaliseString();
			$this->changeToState(CONVERTER_STATE_DEFAULT);
		}
	}


	function	finaliseString(){

		if(count($this->valueElements) == 0){
			$this->finaliseWithoutKey();
		}
		else{
			$this->finaliseWithKey();
		}
	}

	function	finaliseWithoutKey(){
		$array =  trim(implode('', $this->arrayElements));
		$value = trim(implode('', $this->keyOrValueElements));

		$this->stateMachine->addJS( "for (var {$value}Key in $array) {".
			"		\n                 var $value = $array"."[{$value}Key];");

		$this->stateMachine->currentScope->addScopedVariable($value, 0);
	}

	function	finaliseWithKey(){
		$array =  trim(implode('', $this->arrayElements));
		$key = trim(implode('', $this->keyOrValueElements));
		$value = trim(implode('', $this->valueElements));

		$this->stateMachine->addJS("for (var $key in $array) {".
			"\n       var $value = $array"."[$key];");

		$this->stateMachine->currentScope->addScopedVariable($key, 0);
		$this->stateMachine->currentScope->addScopedVariable($value, 0);
	}
}




?>