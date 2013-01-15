<?php

define('FILE_TYPE_IMAGE', 'SHOMOAN');


class Content{

	var $contentID;
	var $text;

	private $id;

	function	__construct($contentID, $text){
		$this->contentID = $contentID;
		$this->text = $text;

		$this->id = 12345;

		echo "FILE_TYPE_IMAGE is ".FILE_TYPE_IMAGE;
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