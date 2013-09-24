<?php
function alert($arg) { }

;
function setTimeout($func, $time = 0) { }

class Str {
	public $length;

	function indexOf($str, $pos = 0) {
		return $this;
	}

	function toLowerCase() {
		return $this;
	}

	/**
	 * @param $sep
	 *
	 * @return Str
	 */
	function split($sep) {
		return $this;
	}

	function join($sep) {
		return $this;
	}

	function lastIndexOf($num) { return 0; }

	function substring($str) { return $this; }

	function __toString() { return ""; }

	/**
	 * @param $args ...
	 *
	 * @return $this
	 */
	function format($args) { return $this; }

	public function substr($param, $int) { return $this; }
}

class Arr {
	public $length;

	/** Joins two or more arrays, and returns a copy of the joined arrays */
	public function concat(){return $this;}
	/** Search the array for an element and returns its position */
	public function indexOf($string){return $this;}
	/** Joins all elements of an array into a string */
	public function join(){return $this;}
	/** Search the array for an element, starting at the end, and returns its position */
	public function lastIndexOf(){return $this;}
	/** Removes the last element of an array, and returns that element */
	public function pop(){return 0;}
	/** Adds new elements to the end of an array, and returns the new length */
	public function push($va){return 1;}
	/** Reverses the order of the elements in an array */
	public function reverse(){return $this;}
	/** Removes the first element of an array, and returns that element */
	public function shift(){return $this;}
	/** Selects a part of an array, and returns the new array */
	public function slice(){return $this;}
	/** Sorts the elements of an array */
	public function sort(){return $this;}
	/** Adds/Removes elements from an array */
	public function splice($pos, $num){return $this;}
	/** Converts an array to a string, and returns the result */
	public function toString(){return $this;}
	/** Adds new elements to the beginning of an array, and returns the new length */
	public function unshift(){return $this;}
	public function valueOf(){return $this;}
}

class RegExp {
	public function __construct($regexp) { }

	/**
	 * @param $str
	 *
	 * @return boolean
	 */
	function test($str) { }
}

class Window {
	public function __set($name, $value) { }

	public function __get($name) { }

	public function open() { return new window(); }
	public function close() { }
	/** @var Window $opener */
	public $top, $window, $location,$external,$chrome,$Intl,$v8Intl,$document,$eventLog,$loadTimeData,$global,$cr,$ntp,$i18nTemplate,$i,$speechSynthesis,$webkitNotifications,$localStorage,
		$sessionStorage,$applicationCache,$webkitStorageInfo,$indexedDB,$webkitIndexedDB,$crypto,$CSS,$performance,$console,$devicePixelRatio,$styleMedia,$parent,$opener,$frames,$self,
		$defaultstatus,$defaultStatus,$status,$name,$length,$closed,$pageYOffset,$pageXOffset,$scrollY,$scrollX,$screenTop,$screenLeft,$screenY,$screenX,$innerWidth,$innerHeight,$outerWidth,
		$outerHeight,$offscreenBuffering,$frameElement,$clientInformation,$navigator,$toolbar,$statusbar,$scrollbars,$personalbar,$menubar,$locationbar,$history,$screen,$ondeviceorientation,
		$ontransitionend,$onwebkittransitionend,$onwebkitanimationstart,$onwebkitanimationiteration,$onwebkitanimationend,$onsearch,$onreset,$onwaiting,$onvolumechange,$onunload,$ontimeupdate,
		$onsuspend,$onsubmit,$onstorage,$onstalled,$onselect,$onseeking,$onseeked,$onscroll,$onresize,$onratechange,$onprogress,$onpopstate,$onplaying,$onplay,$onpause,$onpageshow,$onpagehide,
		$ononline,$onoffline,$onmousewheel,$onmouseup,$onmouseover,$onmouseout,$onmousemove,$onmousedown,$onmessage,$onloadstart,$onloadedmetadata,$onloadeddata,$onload,$onkeyup,$onkeypress,
		$onkeydown,$oninvalid,$oninput,$onhashchange,$onfocus,$onerror,$onended,$onemptied,$ondurationchange,$ondrop,$ondragstart,$ondragover,$ondragleave,$ondragenter,$ondragend,$ondrag,
		$ondblclick,$oncontextmenu,$onclick,$onchange,$oncanplaythrough,$oncanplay,$onblur,$onbeforeunload,$onabort,$TEMPORARY,$PERSISTENT;

	public function logEvent(){return $this;}
	public function EventTracker(){return $this;}
	public function parseHtmlSubset(){return $this;}
	public function assert(){return $this;}
	public function chromeSend(){return $this;}
	public function getSupportedScaleFactors(){return $this;}
	public function url(){return $this;}
	public function imageset(){return $this;}
	public function parseQueryParams(){return $this;}
	public function findAncestorByClass(){return $this;}
	public function findAncestor(){return $this;}
	public function swapDomNodes(){return $this;}
	public function disableTextSelectAndDrag(){return $this;}
	public function preventDefaultOnPoundLinkClicks(){return $this;}
	public function isRTL(){return $this;}
	public function getRequiredElement(){return $this;}
	public function appendParam(){return $this;}
	public function getFaviconImageSet(){return $this;}
	public function getFaviconUrlForCurrentDevicePixelRatio(){return $this;}
	public function createElementWithClassName(){return $this;}
	public function toCssPx(){return $this;}
	public function postMessage(){return $this;}
	public function blur(){return $this;}
	public function focus(){return $this;}
	public function getSelection(){return $this;}
	public function stop(){return $this;}
	public function showModalDialog(){return $this;}
	public function alert(){return $this;}
	public function confirm(){return $this;}
	public function prompt(){return $this;}
	public function find(){return $this;}
	public function scrollBy(){return $this;}
	public function scrollTo(){return $this;}
	public function scroll(){return $this;}
	public function moveBy(){return $this;}
	public function moveTo(){return $this;}
	public function resizeBy(){return $this;}
	public function resizeTo(){return $this;}
	public function matchMedia(){return $this;}
	public function requestAnimationFrame(){return $this;}
	public function cancelAnimationFrame(){return $this;}
	public function webkitRequestAnimationFrame(){return $this;}
	public function webkitCancelAnimationFrame(){return $this;}
	public function webkitCancelRequestAnimationFrame(){return $this;}
	public function atob(){return $this;}
	public function btoa(){return $this;}
	public function addEventListener(){return $this;}
	public function removeEventListener(){return $this;}
	public function captureEvents(){return $this;}
	public function releaseEvents(){return $this;}
	public function setTimeout(){return $this;}
	public function clearTimeout(){return $this;}
	public function setInterval(){return $this;}
	public function clearInterval(){return $this;}
	public function getComputedStyle(){return $this;}
	public function getMatchedCSSRules(){return $this;}
	public function webkitConvertPointFromPageToNode(){return $this;}
	public function webkitConvertPointFromNodeToPage(){return $this;}
	public function dispatchEvent(){return $this;}
	public function webkitRequestFileSystem(){return $this;}
	public function webkitResolveLocalFileSystemURL(){return $this;}
	public function openDatabase(){return $this;}
}

class JsEvent{
	function stopPropagation(){}
}

class Screen {
	public $width, $height;
}

class Date {
	function __construct($date = null) { }
}

class Console {
	/**
	 * @param $args ...
	 */
	public function log($args) { }

	public function debug(){return $this;}
	public function error(){return $this;}
	public function info(){return $this;}
	public function warn(){return $this;}
	public function dir(){return $this;}
	public function dirxml(){return $this;}
	public function table(){return $this;}
	public function trace(){return $this;}
	public function assert(){return $this;}
	public function count(){return $this;}
	public function markTimeline(){return $this;}
	public function profile(){return $this;}
	public function profileEnd(){return $this;}
	public function time(){return $this;}
	public function timeEnd(){return $this;}
	public function timeStamp(){return $this;}
	public function group(){return $this;}
	public function groupCollapsed(){return $this;}
	public function groupEnd(){return $this;}
	public function clear(){return $this;}
}

class Math{
	public static function random(){}
	public static function round($val){}
}

$screen  = new Screen();
$window  = new Window();
$console = new Console();
global $screen, $window, $console;