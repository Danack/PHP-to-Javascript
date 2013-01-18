<?php


class TokenStream{

	/** @var array holds tokens of the php file being converted */
	private $tokens;

	/** @var int number of tokens */
	private $count;

	/** @var int the current token */
	private $current = 0;

	public function __construct($code){
		$this->tokens = token_get_all($code);
		$this->count = count($this->tokens);
	}

	function	hasMoreTokens(){
		if ($this->current < $this->count){
			return TRUE;
		}

		return FALSE;
	}

	function next(&$name, &$value) {
		$token = $this->tokens[$this->current];

		if (is_array($token)) {
			$name = trim(token_name($token[0]));
			$value = $token[1];
		}
		else {
			$name = trim($token);
			$value = '';
		}
		$this->current++;
	}

	function	getCurrentIndex(){
		return $this->current;
	}
}


?>