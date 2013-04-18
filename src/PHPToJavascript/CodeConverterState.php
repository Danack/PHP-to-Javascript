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

	function	changeToState($newState, $extraParams = array()){
		$this->stateMachine->changeToState($newState, $extraParams);
	}

	public function		enterState($extraParams = array()){
	}

	/**
	 * @param $name
	 * @param $value
	 * @return bool Whether the token should be reprocessed by the new state
	 */
	abstract function	processToken($name, $value, $parsedToken);

}


?>