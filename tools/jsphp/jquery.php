<?php
class Q{
    public $responseText;
    static function ajax(array $data){return new self;}
    static function get(array $data){return new self;}
    static function post(array $data){return new self;}
	function html($html){}
	function appendTo($html){return $this;}
	function remove(){return $this;}
	function parent(){return $this;}
	function css(){return $this;}
}

/**
 * @param ... $q
 *
 * @return Q
 */
function Q($q){
	return new Q();
}