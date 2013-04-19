<?php
namespace PHPToJavascript;

class TokenStream{

	/** @var array holds tokens of the php file being converted */
	private $tokens;

	/** @var int number of tokens */
	//private $count;

	/** @var int the current token */
	private $current = 0;

	public function __construct($code){
		$this->tokens = token_get_all($code);
		//$this->count = count($this->tokens);
	}

	function	hasMoreTokens(){
		if ($this->current < count($this->tokens)){
			return TRUE;
		}

		return FALSE;
	}

	function getTokenAtIndex($index, &$name, &$value) {
		$token = $this->tokens[$index];

		if (is_array($token)) {
			$name = trim(token_name($token[0]));
			$value = $token[1];
		}
		else {
			$name = trim($token);
			$value = '';
		}
	}

	function next(&$name, &$value) {
		$this->getTokenAtIndex($this->current, $name, $value);
		$this->current++;
	}

	function	getCurrentIndex(){
		return $this->current;
	}

	function insertToken($name, $value = false) {
		$token = array(
			$name,
			$value,
		);

		array_splice($this->tokens, $this->current, 0, array($token));

		if ($value === false) {
			$this->tokens[$this->current] = $name;
		}
	}


	function	getPreviousNonWhitespaceToken(&$name, &$value) {
		$indexToReturn = $this->current - 2; //-1 gets back to the current token, -2 is the previous token.

		do{
			$this->getTokenAtIndex($indexToReturn, $name, $value);
			if ($name == 'T_COMMENT' ||
				$name == 'T_WHITESPACE') {
				//Keep going
			}
			else{
				//We are done.
				return;
			}
			$indexToReturn -= 1;
		} while($indexToReturn >= 0);

		$name = null; //Could throw an exception?
		$value = null;
	}
}


?>