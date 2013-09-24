<?php
/** @method Q empty */
class Q{
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

	public function submit($func) { return $this; }
	public function attr($name, $value) { return $this; }
	public function load($win) { return $this; }

	public function toArray($arg=null){return $this;}
	public function pushStack($arg=null){return $this;}
	public function each($arg=null){return $this;}
	public function ready($arg=null){return $this;}
	public function slice($arg=null){return $this;}
	public function first($arg=null){return $this;}
	public function last($arg=null){return $this;}
	public function eq($arg=null){return $this;}
	public function map($arg=null){return $this;}
	public function end($arg=null){return $this;}
	public function push($arg=null){return $this;}
	public function sort($arg=null){return $this;}
	public function splice($arg=null){return $this;}
	public function extend($arg=null){return $this;}
	public function data($arg=null){return $this;}
	public function removeData($arg=null){return $this;}
	public function queue($arg=null){return $this;}
	public function dequeue($arg=null){return $this;}
	public function delay($arg=null){return $this;}
	public function clearQueue($arg=null){return $this;}
	public function promise($arg=null){return $this;}
	public function removeAttr($arg=null){return $this;}
	public function prop($arg=null){return $this;}
	public function removeProp($arg=null){return $this;}
	public function toggleClass($arg=null){return $this;}
	public function hasClass($arg=null){return $this;}
	public function on($arg=null){return $this;}
	public function one($arg=null){return $this;}
	public function off($arg=null){return $this;}
	public function trigger($arg=null){return $this;}
	public function triggerHandler($arg=null){return $this;}
	public function has($arg=null){return $this;}
	public function not($arg=null){return $this;}
	public function filter($arg=null){return $this;}
	public function is($arg=null){return $this;}
	public function closest($arg=null){return $this;}
	public function index($arg=null){return $this;}
	public function add($arg=null){return $this;}
	public function addBack($arg=null){return $this;}
	public function parents($arg=null){return $this;}
	public function parentsUntil($arg=null){return $this;}
	public function next($arg=null){return $this;}
	public function prev($arg=null){return $this;}
	public function nextAll($arg=null){return $this;}
	public function prevAll($arg=null){return $this;}
	public function nextUntil($arg=null){return $this;}
	public function prevUntil($arg=null){return $this;}
	public function siblings($arg=null){return $this;}
	public function children($arg=null){return $this;}
	public function contents($arg=null){return $this;}
	public function text($arg=null){return $this;}
	public function prepend($arg=null){return $this;}
	public function before($arg=null){return $this;}
	public function after($arg=null){return $this;}
	public function replaceWith($arg=null){return $this;}
	public function detach($arg=null){return $this;}
	public function domManip($arg=null){return $this;}
	public function prependTo($arg=null){return $this;}
	public function insertBefore($arg=null){return $this;}
	public function insertAfter($arg=null){return $this;}
	public function replaceAll($arg=null){return $this;}
	public function wrapAll($arg=null){return $this;}
	public function wrapInner($arg=null){return $this;}
	public function wrap($arg=null){return $this;}
	public function unwrap($arg=null){return $this;}
	public function show($arg=null){return $this;}
	public function hide($arg=null){return $this;}
	public function toggle($arg=null){return $this;}
	public function serialize($arg=null){return $this;}
	public function serializeArray($arg=null){return $this;}
	public function blur($arg=null){return $this;}
	public function focusin($arg=null){return $this;}
	public function resize($arg=null){return $this;}
	public function scroll($arg=null){return $this;}
	public function unload($arg=null){return $this;}
	public function dblclick($arg=null){return $this;}
	public function mousedown($arg=null){return $this;}
	public function mouseup($arg=null){return $this;}
	public function mousemove($arg=null){return $this;}
	public function mouseover($arg=null){return $this;}
	public function mouseout($arg=null){return $this;}
	public function mouseenter($arg=null){return $this;}
	public function mouseleave($arg=null){return $this;}
	public function change($arg=null){return $this;}
	public function select($arg=null){return $this;}
	public function keydown($arg=null){return $this;}
	public function keypress($arg=null){return $this;}
	public function error($arg=null){return $this;}
	public function contextmenu($arg=null){return $this;}
	public function hover($arg=null){return $this;}
	public function bind($arg=null){return $this;}
	public function unbind($arg=null){return $this;}
	public function delegate($arg=null){return $this;}
	public function undelegate($arg=null){return $this;}
	public function ajaxStart($arg=null){return $this;}
	public function ajaxStop($arg=null){return $this;}
	public function ajaxComplete($arg=null){return $this;}
	public function ajaxError($arg=null){return $this;}
	public function ajaxSuccess($arg=null){return $this;}
	public function ajaxSend($arg=null){return $this;}
	public function fadeTo($arg=null){return $this;}
	public function animate($arg=null){return $this;}
	public function stop($arg=null){return $this;}
	public function finish($arg=null){return $this;}
	public function slideDown($arg=null){return $this;}
	public function slideUp($arg=null){return $this;}
	public function slideToggle($arg=null){return $this;}
	public function fadeIn($arg=null){return $this;}
	public function fadeOut($arg=null){return $this;}
	public function fadeToggle($arg=null){return $this;}
	public function offset($arg=null){return $this;}
	public function position($arg=null){return $this;}
	public function offsetParent($arg=null){return $this;}
	public function scrollLeft($arg=null){return $this;}
	public function scrollTop($arg=null){return $this;}
	public function innerHeight($arg=null){return $this;}
	public function height($arg=null){return $this;}
	public function outerHeight($arg=null){return $this;}
	public function innerWidth($arg=null){return $this;}
	public function width($arg=null){return $this;}
	public function outerWidth($arg=null){return $this;}
	public function size($arg=null){return $this;}
	public function andSelf($arg=null){return $this;}
	public function scrollParent($arg=null){return $this;}
	public function zIndex($arg=null){return $this;}
	public function uniqueId($arg=null){return $this;}
	public function removeUniqueId($arg=null){return $this;}
	public function disableSelection($arg=null){return $this;}
	public function enableSelection($arg=null){return $this;}
	public function mouse($arg=null){return $this;}
	public function draggable($arg=null){return $this;}
	public function droppable($arg=null){return $this;}
	public function resizable($arg=null){return $this;}
	public function selectable($arg=null){return $this;}
	public function sortable($arg=null){return $this;}
	public function accordion($arg=null){return $this;}
	public function autocomplete($arg=null){return $this;}
	public function button($arg=null){return $this;}
	public function buttonset($arg=null){return $this;}
	public function datepicker($arg=null){return $this;}
	public function dialog($arg=null){return $this;}
	public function menu($arg=null){return $this;}
	public function progressbar($arg=null){return $this;}
	public function slider($arg=null){return $this;}
	public function spinner($arg=null){return $this;}
	public function tabs($arg=null){return $this;}
	public function tooltip($arg=null){return $this;}
	public function switchClass($arg=null){return $this;}
	public function effect($arg=null){return $this;}
	public function cssUnit($arg=null){return $this;}
	public function alert($arg=null){return $this;}
	public function carousel($arg=null){return $this;}
	public function collapse($arg=null){return $this;}
	public function modal($arg=null){return $this;}
	public function popover($arg=null){return $this;}
	public function scrollspy($arg=null){return $this;}
	public function tab($arg=null){return $this;}
	public function typeahead($arg=null){return $this;}
	public function affix($arg=null){return $this;}

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
