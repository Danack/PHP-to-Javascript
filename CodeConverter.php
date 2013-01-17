<?php

require_once('TokenStream.php');

require_once('CodeScope.php');
require_once('ConverterStateMachine.php');
require_once('ConverterStates.php');




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
		$this->stateMachine = new ConverterStateMachine($this->tokenStream, CONVERTER_STATE_DEFAULT, $isClassScope);
	}

	function	toJavascript(){
		$name = '';
		$value = '';

		while($this->tokenStream->moreTokens() == TRUE){
			$this->tokenStream->next($name, $value);

			$count = 0;

			do{
				$parsedToken = $this->stateMachine->parseToken($name, $value);

				$reprocess = $this->stateMachine->processToken($name, $value, $parsedToken);

				if($count > 5){
					throw new Exception("Stuck converting same token.");
				}

				$count++;
			}
			while($reprocess == TRUE);
		}

		return $this->stateMachine->finalize();
	}
}


?>