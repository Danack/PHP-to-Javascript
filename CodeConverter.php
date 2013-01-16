<?php

require_once('TokenStream.php');


class CodeConverter{

	/**
	 * @var TokenStream
	 */
	public $tokenStream;

	/**
	 * @var ConverterStateMachine The state machine for processing the code tokens.
	 */
	public $stateMachine;

	function	__construct($code, $isClassScope){
		$this->tokenStream = new TokenStream($code);
		$this->stateMachine = new ConverterStateMachine(CONVERTER_STATE_DEFAULT, $isClassScope);
	}

	function	toJavascript(){
		$name = '';
		$value = '';

		while($this->tokenStream->moreTokens() == TRUE){
			$this->tokenStream->next($name, $value);

			do{
				$reprocess = $this->stateMachine->processToken($name, $value);
			}
			while($reprocess == TRUE);
		}

		return implode("\n", $this->stateMachine->getJSArray());
	}
}


?>