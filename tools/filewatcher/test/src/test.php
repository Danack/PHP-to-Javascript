<?php
interface FooInt {
	function fooInt();
}

abstract class FooAbs {
	abstract function fooAbsAbs();

	function fooAbs() {
		echo "--fooAbs--";
		for ($i = 0; $i < 10; $i++) {
			$e = ($i * 10 - $i) . " foo in abs";
			echo $e;
		}
	}
}

class Foo extends FooAbs implements FooInt {
	private $foo = "foo";

	function __construct($fooCon) {
		echo "--__construct--";
		$this->foo = $fooCon;
	}

	function fooInt() {
		echo "--fooInt--";
	}

	function fooAbsAbs() {
		echo "--fooAbsAbs--";
	}

	function getFoo() {
		echo "--getFoo--";
		return $this->foo;
	}
}

$foo = new Foo("foo2");
echo $foo->getFoo();
$foo->fooInt();
$foo->fooAbsAbs();
$foo->fooAbs();





class Foo3{
	protected $foo=5;

	function foooo(){
		$foo==6;
	}
}
class Foo5 extends Foo3{

	function foooo(){
		$this->foo=6;
		parent::foooo();
	}
}












