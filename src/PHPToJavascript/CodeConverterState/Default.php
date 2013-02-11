<?php

namespace PHPToJavascript;

class CodeConverterState_Default extends CodeConverterState {

	/**
	 * @var array List of tokens that will trigger a change to the appropriate state.
	 */
	public $tokenStateChangeList = array(
		'T_ECHO' 		=> CONVERTER_STATE_ECHO,
		'T_ARRAY'		=> CONVERTER_STATE_ARRAY,
		'T_CLASS'		=> CONVERTER_STATE_CLASS,
		'T_FUNCTION'	=> CONVERTER_STATE_FUNCTION,
		'T_FOREACH'		=> CONVERTER_STATE_FOREACH,
		'T_PUBLIC'		=> CONVERTER_STATE_PUBLIC,
		'T_VARIABLE'	=> CONVERTER_STATE_VARIABLE,
		'T_STATIC'		=> CONVERTER_STATE_STATIC,
		'T_STRING'		=> CONVERTER_STATE_STRING,
		'T_VAR' 		=> CONVERTER_STATE_T_PUBLIC,
		'T_PRIVATE'		=> CONVERTER_STATE_T_PRIVATE,

		'T_EXTENDS'		=> CONVERTER_STATE_T_EXTENDS,
		'T_NEW'			=> CONVERTER_STATE_T_NEW,
		'T_CONSTANT_ENCAPSED_STRING' => CONVERTER_STATE_T_CONSTANT_ENCAPSED_STRING,
		'='					=> CONVERTER_STATE_EQUALS,
		')' 				=> CONVERTER_STATE_CLOSE_PARENS,
		'T_REQUIRE_ONCE'	=> CONVERTER_STATE_REQUIRE,
		'T_IMPLEMENTS' 		=>	CONVERTER_STATE_IMPLEMENTS_INTERFACE,

		'T_ABSTRACT' 		=>	CONVERTER_STATE_ABSTRACT,

		'T_INTERFACE'		=> CONVERTER_STATE_INTERFACE,
		'T_OBJECT_OPERATOR' => CONVERTER_STATE_OBJECT_OPERATOR,
	);

	function	processToken($name, $value, $parsedToken){
		if($name == 'T_STRING'){
			if($value == 'define'){
				$this->changeToState(CONVERTER_STATE_DEFINE);
				return TRUE;
			}
		}

		if(array_key_exists($name, $this->tokenStateChangeList) == TRUE){
			$this->changeToState($this->tokenStateChangeList[$name]);
			return TRUE;
		}

		$js = $parsedToken;
		$this->stateMachine->addJS($js);

		if($name == '{'){
			if($this->stateMachine->currentScope->startOfFunction() == TRUE){
				$this->stateMachine->addDefaultsForVariables();
			}
		}


//		if($name == '{'){
//			if($this->stateMachine->currentScope->endOfClass() == TRUE){
//				$this->stateMachine->addClassBindingMagic();
//			}
//		}


		return FALSE;
	}
}




?>