<?php
require_once '../PHP2js.php';
new PHP2js(__FILE__);

class TestArray {
	
	public function single() {
		$a = array('What\'s', 'the', 'matrix?');
		foreach ($a as $key=>$val) {
			echo $val.' ';
		}
		echo '<hr />';
	}
	
	public function assoc() {
		$a = array(
			'Im'=>'have',
			'a'=>'life'
		);
		foreach ($a as $key=>$val) {
			echo $key.' '.$val.' ';
		}
		echo '<hr />';
	}
	
	public function assocWithVariables($a, $b, $c) {
		$d = array(
			'a' => $a,
			'b' => $a,
			'c' => $c,
		);
		foreach ($d as $key=>$val) {
			echo $key.': '.$val.', ';
		}
		echo '<hr />';
	}
	
}
$T = new TestArray();
$T->single();
$T->assoc();
$T->assocWithVariables('hallo', 'and', 'goodbye');
?>