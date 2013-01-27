<?php


class TestClass{

	private $privateVar = 0;
	public	$publicVar = 0;

	function	testFunction(){
		return $this->privateVar + $this->publicVar;
	}

}





?>