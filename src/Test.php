<script src="../php.js"></script>
<?
require_once 'PHP2js.php';
new PHP2js('Test.php');
/**
 * My super cool php class that will be converted to js!!!
 */
class test {
	/**
	 * a class var
	 *
	 * @var stringy
	 */
	public $testVar = '<br />hello textvar';
	/**
	 * So here goes a function that echos
	 *
	 * @param string $foo
	 * @param string $bar
	 */
	function tEcho($foo, $bar) {
		echo $foo . ' ' . $bar;
	}
	
	function write($string) {
		echo '<br />'.$string;
	}
	/**
	 * if
	 *
	 * @param string $bazRa
	 */
	function tIf($bazRa) {
		//an if sentence
		if ($bazRa == '1234abc') {
			echo '<br />'.$bazRa;
		}
	}
	
	/**
	 * returning stuff
	 *
	 * @param int $a
	 */
	function tReturn() {
		return '<br>hey';
	}
	
	/**
	 * math
	 */
	
	function tMath($dasInt) {
		$this->write ($dasInt * 3000);
		$this->write ($dasInt / 3000);
		$this->write ($dasInt - 3000);
		$this->write ($dasInt + 3000);
	}
	
	function tOperators ($a, $b) {
		if ($a > $b) {
			$this->write('a is bigger than b');
		}
		
		if ($a < $b) {
			$this->write('a is smaller than b');
		}
		
		if ($a == $b) {
			$this->write('a is the same as b');
		}
		
		if ($a === $b) {
			$this->write('a is b');
		}
		
		if ($a != $b) {
			$this->write('a is not the same as b');
		}
		
		if ($a !== $b) {
			$this->write('a is not b');
		}
		
		if ($a >= $b) {
			$this->write('a the same or bigger than b');
		}
		
		if ($a <= $b) {
			$this->write('a the same or smaller than b');
		}
	}
	
	function tElse ($a) {
		if ($a == '999') {
			$this->write('im 999');
		} else if ($a == '1000'){
			$this->write('im 1000');
		} else {
			$this->write('im not 1000');
		}
	}

	function tPhpJs1 ($abe) {
		return strlen($abe);
	}

	function tPhpJs4 () {
		$singleArray = array('love', 'hate', 'sex');
		foreach ($singleArray as $val) {
			echo '<br />Value: '.$val;
		}
	}
	
	function tPhpJs5 () {
		$assocArray = array('love'=>'you', 'hate'=>'some', 'sun'=>'good', 'moon'=>'sex');
		foreach ($assocArray as $key=>$val) {
			echo '<br />Key: '.$key.' is value '.$val;
		}
	}
	
	function tWhile() {
		echo '<br />';
		$i = 1;
		while ($i < 11) {
			echo $i.' ';
			$i++;
		}
	}
}
//setup
$foo = 'Hello';
$bar = 'World';

//init class
$Cl = new test();

//testing
$Cl->tEcho($foo, $bar);
$Cl->tIf('1234abc');
echo $Cl->tReturn(4);
$Cl->tMath(999);
$Cl->tOperators (10, 5);
$Cl->tOperators (-5, 10);
$Cl->tOperators ('5', 5);
$Cl->tOperators (5, 5);
$Cl->tElse('998');
$Cl->tElse('999');
$Cl->tElse('1000');
echo "<a name='anchor' ></a>";
echo '<br />Im '. $Cl->tPhpJs1('Count me NOW'). ' characters long';
$Cl->tPhpJs4();
$Cl->tPhpJs5();
$Cl->tWhile();
echo $Cl->testVar;
?>