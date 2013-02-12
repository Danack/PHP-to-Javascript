<?php

namespace PHPToJavascript;

class CodeConverterState_Echo extends CodeConverterState {

	/**
	 * The function that PHP echo be replaced with. This can be overwritten to allow custom
	 * output functions.
	 *
	 * @var string
	 */
	//static public 	$echoConversionFunction = PHPToJavascript::ECHO_TO_DOCUMENT_WRITE;
	static public 	$echoConversionFunction = "";// = PHPToJavascript::$ECHO_TO_ALERT;

	function __construct(ConverterStateMachine $stateMachine){
		parent::__construct($stateMachine);
		self::$echoConversionFunction = PHPToJavascript::$ECHO_TO_ALERT;
	}

	static function	setEchoConversionFunction($newEchoConversionFunction){
		self::$echoConversionFunction = $newEchoConversionFunction;
	}


	public function		enterState($extraParams = array()){
		parent::enterState($extraParams);
	}

	function	processToken($name, $value, $parsedToken){
		$this->stateMachine->addJS(self::$echoConversionFunction);
		$this->stateMachine->addJS($parsedToken);

		$this->stateMachine->setPendingSymbol(';', ")");
		$this->changeToState(CONVERTER_STATE_DEFAULT);
		return FALSE;
	}
}



?>