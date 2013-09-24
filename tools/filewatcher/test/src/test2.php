<?php

class Foo{
	function foo(){
		global $var , $foo;
		$foo2=$foo;
		$var2=$var;
	}
}
