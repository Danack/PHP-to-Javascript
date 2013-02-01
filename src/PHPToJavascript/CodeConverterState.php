<?php

namespace PHPToJavascript;


abstract class CodeConverterState{

	/**
	 * @var ConverterStateMachine
	 */
	protected $stateMachine = NULL;

	function __construct(ConverterStateMachine $stateMachine){
		$this->stateMachine = $stateMachine;
	}

	function	changeToState($newState){
		$this->stateMachine->changeToState($newState);
	}

	public function		enterState($extraParams = array()){
	}
	public function		exitState($extraParams){
	}

	/**
	 * @param $name
	 * @param $value
	 * @return bool Whether the token should be reprocessed by the new state
	 */
	abstract function	processToken($name, $value, $parsedToken);

}




/*
class CodeConverterState_T_PUBLIC extends CodeConverterState {

	var	$stateChunk = '';

	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
		$this->stateChunk = '';
	}

	function	processToken($name, $value, $parsedToken){

		$type = $this->findFirst(array('T_VARIABLE', 'T_FUNCTION'));

		if ($type == 'T_FUNCTION'){
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return;
		}

		//$parsedToken = $this->parseToken($name, $value);
		$this->stateChunk .= $parsedToken;

		if ($name == ';') {
			$js = str_replace(array(' '), '', $this->stateChunk);
			$result = 'this.'.$this->stateChunk;
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return $result;
		}

		if($name == '='){
			$this->changeToState('Default');
			$js = str_replace(array(' ','='), '', $this->stateChunk);
			$result = 'this.'.$js.' =';
			$this->changeToState(CONVERTER_STATE_DEFAULT);
			return $result;
		}
	}
} */


/*class CodeConverterState_IMPLEMENTS_INTERFACE extends CodeConverterState{

	function	processToken($name, $value, $parsedToken){
//		if($this->stateMachine->currentScope instanceof FunctionParameterScope){
//			$this->stateMachine->pushScope(
//				CODE_SCOPE_FUNCTION,
//				$this->stateMachine->currentScope->getName()
//			);
//		}
//
//		$this->stateMachine->addJS(')');

		$this->changeToState(CONVERTER_STATE_DEFAULT);
	}
}*/



?>