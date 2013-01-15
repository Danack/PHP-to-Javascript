<?php

class Content{

	var $contentID;
	var $text;

	private $id;

	function	__construct($contentID, $text){
		$this->contentID = $contentID;
		$this->text = $text;

		$this->id = 12345;
	}

	function	getThumbnailURL($shamoan){
		$output = "/proxy/";
		$output .= $this->contentID;
		$output .= "/thumbnail/";
		$output .= $this->text;

		return urlencode($output);
	}
}

?>