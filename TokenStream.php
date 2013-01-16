<?php


class TokenStream{

	/** @var array holds tokens of the php file being converted */
	private $_tokens;

	/** @var int number of tokens */
	private $count;

	/** @var int the current token */
	private $current = 0;

	public function __construct($code){
		$this->_tokens = token_get_all($code);
		$this->count = count($this->_tokens);
	}

	function	getTokenNameValue(&$name, &$value, $tokenIndex){
		$token = $this->_tokens[$tokenIndex];

		if (is_array($token)) {
			$name = trim(token_name($token[0]));
			$value = $token[1];
		}
		else {
			$name = trim($token);
			$value = '';
		}
	}

	function	moreTokens(){
		if ($this->current < $this->count){
			return TRUE;
		}

		return FALSE;
	}

	function	skipTokens($i){
		$this->current += $i;
	}

	function next(&$name, &$value) {
		$this->getTokenNameValue($name, $value, $this->current);
		$this->current++;
	}

	/**
	 * find and return first name matching argument
	 *
	 * @param mixed $_tokenNames
	 * @return string
	 */
	private function findFirst($_needles){

		$name = $value = '';

		for ($i=$this->current + 1 ; $i<$this->count; $i++) {
			$this->getTokenNameValue($name, $value, $i);
			if (in_array($name, (array)$_needles)) {
				return $name;
			}
		}
	}
}


?>