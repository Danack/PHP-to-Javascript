<?php
class Q{
	// TODO: implement all jquery methods
    public $responseText;
    static function ajax(array $data){return new self;}
    static function get(array $data){return new self;}
    static function post(array $data){return new self;}
	function html($html){}
	function appendTo($html){return $this;}
	function append($html){return $this;}
	function remove(){return $this;}
	function parent(){return $this;}
	function css(){return $this;}
	function val(){return $this;}
	function addClass($class){return $this;}
	function removeClass($class){return $this;}
	public function find($schedule) {return $this;}

	// Events
	function focus($func){return $this;}
	function focusout($func){return $this;}

	function keyup($func){return $this;}
	function click(){return $this;}

	// Plugins
	function dropdown(){return $this;}

}
$Q = new Q();
global $Q;
/**
 * @param ... $q
 *
 * @return Q
 */
function JQ($q=null){
	return new Q();
}
