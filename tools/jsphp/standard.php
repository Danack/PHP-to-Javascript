<?php
// TODO: finalize this file with all js features
function alert($arg){};
class Str{
	public $length;
    function indexOf($str,$pos=0){
        return $this;
    }
    function toLowerCase(){
        return $this;
    }

	/**
	 * @param $sep
	 *
	 * @return Str
	 */
	function split($sep){
		return $this;
	}
	function join($sep){
		return $this;
	}
	function lastIndexOf($num){return 0;}
	function substring($str){return $this;}
	function __toString(){return "";}
}

class Arr{
	public $length;
    function push($va){return 1;}
	function splice($pos,$num){return $this;}
}

class RegExp{
	public function __construct($regexp){}

	/**
	 * @param $str
	 * @return boolean
	 */
	function test($str){}
}

class Window{

	public function __set($name,$value){}
	public function __get($name){}
}
$window = new Window();
global $window;
