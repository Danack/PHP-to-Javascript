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

		'T_TRAIT'		=> CONVERTER_STATE_CLASS, //traits are effectively the same as classes
		'T_FUNCTION'	=> CONVERTER_STATE_FUNCTION,
		'T_FOREACH'		=> CONVERTER_STATE_FOREACH,
		'T_PUBLIC'		=> CONVERTER_STATE_PUBLIC,
		'T_VARIABLE'	=> CONVERTER_STATE_VARIABLE,
		'T_STATIC'		=> CONVERTER_STATE_STATIC,
		'T_STRING'		=> CONVERTER_STATE_STRING,
		'T_VAR' 		=> CONVERTER_STATE_T_PUBLIC,
		'T_PRIVATE'		=> CONVERTER_STATE_T_PRIVATE,

		'T_EXTENDS'		=> CONVERTER_STATE_T_EXTENDS,
		'T_USE'			=> CONVERTER_STATE_T_USE,

		'T_NEW'			=> CONVERTER_STATE_T_NEW,
		'T_CONSTANT_ENCAPSED_STRING' => CONVERTER_STATE_VARIABLE_DEFAULT,
		'='					=> CONVERTER_STATE_EQUALS,
		')' 				=> CONVERTER_STATE_CLOSE_PARENS,
		'T_REQUIRE_ONCE'	=> CONVERTER_STATE_REQUIRE,
		'T_IMPLEMENTS' 		=>	CONVERTER_STATE_IMPLEMENTS_INTERFACE,

		'T_ABSTRACT' 		=>	CONVERTER_STATE_ABSTRACT,

		'T_INTERFACE'		=> CONVERTER_STATE_INTERFACE,
		'T_OBJECT_OPERATOR' => CONVERTER_STATE_OBJECT_OPERATOR,
		','					=> CONVERTER_STATE_COMMA,
		'T_DOUBLE_ARROW'	=> CONVERTER_STATE_DOUBLE_ARROW,
		'T_DOUBLE_COLON'	=> CONVERTER_STATE_DOUBLE_COLON,
		'T_NAMESPACE'		=> CONVERTER_STATE_NAME_SPACE,
		'T_UNSET' 			=> CONVERTER_STATE_T_UNSET,
		'T_TRY'				=> CONVERTER_STATE_T_TRY,
		'T_CATCH'			=> CONVERTER_STATE_T_CATCH,
	);

	function	processToken($name, $value, $parsedToken){
		if($name == 'T_STRING'){
			if($value == 'define'){
				$this->changeToState(CONVERTER_STATE_DEFINE);
				return TRUE;
			}
		}

		if ($name == '[') {
			//echo "We were in default state - but encountered a [";

			$previousName = false;
			$previousValue = false;
			$this->stateMachine->getPreviousNonWhitespaceToken($previousName, $previousValue);

			if ($previousName == '=' ||
				$previousName == 'T_DOUBLE_ARROW' ||
				$previousName == ',' ||
				$previousName == '(' ||
				$previousName == '[' ) { //Yep, this is the start of an array declaration.

				$this->stateMachine->startArrayScope("");
				$this->stateMachine->currentTokenStream->insertToken('(');
				return false;
			}
		}

		if ($name == ']') {
			if ($this->stateMachine->currentScope instanceof ArrayScope) {
				$this->stateMachine->currentTokenStream->insertToken(')');
				return false;
			}
		}



		if(array_key_exists($name, $this->tokenStateChangeList) == TRUE){
			$this->changeToState($this->tokenStateChangeList[$name]);
			return TRUE;
		}

		if($name == 'T_LNUMBER'){
			if($this->stateMachine->currentScope instanceof FunctionParameterScope){
				//It's a number as a placeholder for a param e.g.
				//function someFunction($foo = 5){...}
				$this->changeToState(CONVERTER_STATE_VARIABLE_DEFAULT);
				return TRUE;
			}
		}

		$js = $parsedToken;
		$this->stateMachine->addJS($js);

		if($name == '{'){
			if($this->stateMachine->currentScope->startOfFunction() == TRUE){
				$this->stateMachine->addDefaultsForVariables();
			}
		}

		return FALSE;
	}
}




?>