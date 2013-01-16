<?php


//class ProcessingFinishedException extends Exception{};


class CodeTokenizer{

	var $sourceCode;

	/** @var array holds tokens of the php file being converted */
	private $_tokens;
	/** @var int number of tokens */
	private $count;
	/** @var int the current token */
	private $current = 0;
	/** @var javascript gets collected here */
	private $js = '';

	private $isClassScope;

	private $currentMethodName = FALSE;

	private $staticVariable = FALSE;

	private $scopedVariables = array();

	function	__construct($code, $isClassScope){

		$this->_tokens = token_get_all($code);
		//$this->count = count($this->_tokens)-1; //bet it's skipping the closing ? >
		$this->count = count($this->_tokens);

		$this->isClassScope = $isClassScope;

	}

	function	toJavascript(){
		//return 	$this->convertTokens($tokens);

		$this->compileJs();

		return $this->js;
	}

	public function compileJs() {

		$name = '';
		$value = '';

		try{
			foreach ($this->_tokens as $_) {
				$this->next ($name, $value);
				$this->parseToken($name, $value, $this->js);
			}
		}
		catch(ProcessingFinishedException $pfe){
			//Le sigh
		}
	}


	/**
	 * changed referenced args to name and value of next token
	 *
	 * @param string $name
	 * @param string $value
	 * @param unknown_type $i, the amount of nexts to skip
	 */
	private function next(& $name, & $value, $i=1) {
		for ($j=0; $j<$i; $j++) {
			$this->current++;
			if ($this->current >= $this->count){
				//$this->renderJs();
				throw new ProcessingFinishedException();
			}
			$_token = $this->_tokens[$this->current];
			$this->getToken ($name, $value, $_token);
		}
	}

	/**
	 * find and return first name matching argument
	 *
	 * @param mixed $_tokenNames
	 * @return string
	 */
	private function findFirst ($_needles) {
		$name = $value = '';
		for ($i=$this->current+1; $i<$this->count; $i++) {
			$this->getToken($name, $value, $this->_tokens[$i]);
			if (in_array($name, (array)$_needles)) {
				return $name;
			}
		}
	}

	/**
	 * return javascript until match, match not included
	 *
	 * @param array $_needles
	 * @return string
	 */
	private function parseUntil ($_needles, $_schema=array(), $includeMatch = false, $breakOutVariables = TRUE) {
		$name = $value = $js = $tmp = '';

		$previousToken = FALSE;


		while (true) {
			$this->next ($name, $value);

			$backToString = FALSE;

			if($breakOutVariables == TRUE &&
				$name == 'T_VARIABLE' &&
				$previousToken == 'T_ENCAPSED_AND_WHITESPACE'){
				$tmp = $tmp.'" + ';
			}
			if($breakOutVariables == TRUE &&
				($name == 'T_ENCAPSED_AND_WHITESPACE' || $name == 'T_STRING' || $name == '"' || $name == "'" ) &&
				$previousToken == 'T_VARIABLE' ){
				$tmp = $tmp.'+ "';
			}

			$this->parseToken($name, $value, $tmp, $_schema);

			if (in_array($name, (array)$_needles)) {
				if ($includeMatch === true) {
					return $tmp;
				} else {
					return $js;
				}
			}
			$previousToken = $name;
			$js = $tmp;
		}
	}


	private function parseToken ($name, $value, & $js, $_schema=array()) {

		//custom changes
		if (in_array($name, array_keys((array)$_schema))) {
			$js .= $_schema[$name];
			//change name to other value
		}
		else if (in_array($name, array_keys($this->_convert))) {
			$js .= (!empty($this->_convert[$name])) ? $this->_convert[$name] : $name;
			//keep key
		}
		else if (in_array($name, $this->_keep)) {
			$js .= $name;
			//keep value

			if($name == '{'){
				$this->incrementBracketCounter();
			}
			else if($name == '}'){
				$this->decrementBracketCounter();
			}
		}
		else if($name == 'T_STRING' && defined($value)){
			$js .= constant($value);
		}
		else if (in_array($name, $this->_keepValue)) {
			$js .= $value;
			//call method
		}
		else if (method_exists($this, $name)) {
			$js .= $this->$name($value);
		}

		//ignore
	}


	/**
	 * class definition
	 *
	 * @param sting $value
	 * @return string
	 */
	private function T_CLASS($value) {
		$this->next ($name, $value, 2);
		return "function $value()";
	}




	/**
	 * define function
	 *
	 * @param string $value
	 * @return string
	 */
	private function T_FUNCTION($value) {
		$this->next ($name, $value, 2);

		//Used for generating static vars in javascript.
		$this->currentMethodName = $value;

		if($this->isClassScope == TRUE){
			return "this.$value = function";
		}
		else{
			return "function $value ";
		}

		$this->setBracketCounter(0);
	}

	/**
	 * echo is replaced with document.write
	 *
	 * @param string $value
	 * @return string
	 */
	private function T_ECHO($value) {

		//TODO - this is broken;
		// e.g. echo "value is $value";

		return 'document.write('.trim($this->parseUntil(';')).');';
	}

	/**
	 * array. Supports both single and associative
	 *
	 * @param string $value
	 * @return string
	 */
	private function T_ARRAY($value) {
		$_convert = array('('=>'{',	')'=>'}',);
		$js = $this->parseUntil(array(';'), $_convert, true, FALSE);
		if (strpos($js, ':') === false) {
			$this->tmp = -1;
			$js = preg_replace_callback ('/([{, \t\n])(\'.*\')(|.*:(.*))([,} \t\n])/Uis', array($this, 'cb_T_ARRAY'), $js);
		}
		return $js;
	}

	private function cb_T_ARRAY($_matches) {
		$this->tmp++;
		if (strpos($_matches[0], ':') === false) {
			return ($_matches[1].$this->tmp.':'.$_matches[2].$_matches[3].$_matches[4].$_matches[5]);
		} else {
			return $_matches[0];
		}
	}
	/**
	 * foreach. Gets converted to for (var blah in blih). Supports as $key=>$value
	 *
	 * @param string $value
	 * @return string
	 */
	private function T_FOREACH($value) {
		$_vars = array();
		while (true) {
			$this->next ($name, $value);
			if ($name == 'T_VARIABLE') $_vars[] = $this->cVar($value);
			$this->parseToken($name, $value, $js);
			if ($name == '{') {
				if (count($_vars) == 2) {
					$array = $_vars[0];
					$val = $_vars[1];
					$this->js .=
						"for (var {$val}Val in $array) {".
							"\n                        $val = $array"."[{$val}Val];";
				}
				if (count($_vars) == 3) {
					$array = $_vars[0];
					$key = $_vars[1];
					$val = $_vars[2];
					$this->js .=
						"for (var $key in $array) {".
							"\n                        $val = $array"."[$key];";
				}
				return '';
			}
			$jsTmp = $js;
		}
	}



	/**
	 * declare a public class var
	 *
	 * @param string $value
	 * @return string
	 */
	private function T_PUBLIC ($value) {
		$type = $this->findFirst(array('T_VARIABLE', 'T_FUNCTION'));
		if ($type == 'T_FUNCTION') return '';
		$js = '';
		while (true) {
			$this->next ($name, $value);
			$this->parseToken($name, $value, $js);
			if ($name == ';') {
				$js = str_replace(array(' '), '', $js);
				return 'this.'.$js;
			} else if ($name == '=') {
				$js = str_replace(array(' ','='), '', $js);
				return 'this.'.$js.' =';
			}
		}
	}

	/**
	 * variable. Remove the $
	 *
	 * @param string $value
	 * @return string
	 */
	private function T_VARIABLE($value) {

		$variableName = str_replace('$', '', $value);

		$javascript = "";

		if($this->staticVariable == TRUE){
			$javascript .= "if (typeof ".$this->currentMethodName.".$variableName == 'undefined') ";
			$javascript .= $this->currentMethodName.".$variableName";
			$this->staticVariable = FALSE;

			$this->scopedVariables[$variableName] =  $this->currentMethodName.".$variableName";
		}
		else{

			if(array_key_exists($variableName, $this->scopedVariables) == TRUE){
				$variableName = $this->scopedVariables[$variableName];
			}

			$javascript = $variableName;
		}

		return $javascript;
	}


	private function T_STATIC($value){

		$this->staticVariable = TRUE;

		return "";
	}


	/* helpers */

	private function getToken(& $name, & $value, $_token) {
		if (is_array($_token)) {
			$name = trim(token_name($_token[0]));
			$value = $_token[1];
		} else {
			$name = trim($_token);
			$value = '';
		}
	}

	private function cVar($var) {

		return  str_replace('$', '', $var);


	}



	/** @var array these token keys will be converted to their values */
	private $_convert = array (
		'T_IS_EQUAL'=>'==',
		'T_IS_GREATER_OR_EQUAL'=>'>=',
		'T_IS_SMALLER_OR_EQUAL'=>'<=',
		'T_IS_IDENTICAL'=>'===',
		'T_IS_NOT_EQUAL'=>'!=',
		'T_IS_NOT_IDENTICAL'=>'!==',
		'T_IS_SMALLER_OR_EQUA'=>'<=',
		'T_BOOLEAN_AND'=>'&&',
		'T_BOOLEAN_OR'=>'||',
		'T_CONCAT_EQUAL'=>'+= ',
		'T_DIV_EQUAL'=>'/=',
		'T_DOUBLE_COLON'=>'.',
		'T_INC'=>'++',
		'T_MINUS_EQUAL'=>'-=',
		'T_MOD_EQUAL'=>'%=',
		'T_MUL_EQUAL'=>'*=',
		'T_OBJECT_OPERATOR'=>'.',
		'T_OR_EQUAL'=>'|=',
		'T_PLUS_EQUAL'=>'+=',
		'T_SL'=>'<<',
		'T_SL_EQUAL'=>'<<=',
		'T_SR'=>'>>',
		'T_SR_EQUAL'=>'>>=',
		'T_START_HEREDOC'=>'<<<',
		'T_XOR_EQUAL'=>'^=',
		'T_NEW'=>'new',
		'T_ELSE'=>'else',
		'.'=>' + ',
		'T_IF'=>'if',
		'T_RETURN'=>'return',
		'T_AS'=>'in',
		'T_WHILE'=>'while',
		'T_LOGICAL_AND' => 'AND',
		'T_LOGICAL_OR' => 'OR',
		'T_LOGICAL_XOR' => 'XOR',
		'T_EVAL' => 'eval',
		'T_ELSEIF' => 'else if',
		'T_BREAK' => 'break',
		'T_DOUBLE_ARROW' => ':',
	);

	/** @var array these tokens stays the same */
	private $_keep = array(
		'=', ',', '}', '{', ';', '(', ')', '*', '/', '+', '-', '>', '<', '[', ']',

		"\"", "'",
	);

	/** @var array these tokens keeps their value */
	private $_keepValue = array (
		'T_CONSTANT_ENCAPSED_STRING', 'T_STRING', 'T_COMMENT', 'T_ML_COMMENT', 'T_DOC_COMMENT', 'T_LNUMBER', 'T_ENCAPSED_AND_WHITESPACE',
		'T_WHITESPACE',
	);

	var $bracketCount = 0;

	function	setBracketCounter($count){
		$this->bracketCount = $count;
	}

	function incrementBracketCounter(){
		$this->bracketCount++;
	}

	function decrementBracketCounter(){

		$this->bracketCount--;

		if($this->bracketCount == 0){
			$this->endFunction();
		}
	}

	function	endFunction(){
		$this->scopedVariables = array();
	}

}


?>