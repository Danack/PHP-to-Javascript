<?
require_once 'PHP2js.php';
new PHP2js(__FILE__);
/**
 * My super cool php class that will be converted to js!!!
 */
class HelloWorld {
	/**
	 * So here goes a function that echos
	 *
	 * @param string $foo
	 * @param string $bar
	 */
	function foo($foo, $bar) {
		echo $foo . ' ' . $bar;
	}
}
$H = new HelloWorld;
$H->foo('Hello', 'World');
