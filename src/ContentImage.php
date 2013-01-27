<?php

$count = 0;

$count++;

echo "$count";


class ContentImage {

	var		$contentID;
	var		$imageID;
	var		$name;
	var		$storageType;

	function	test($name){
		$this->name = $name;
	}


	function greetWorld($greeting = "Hello there world!"){
		static $count = 0;

		$count++;

		echo $greeting.$count;
	}

	function	toJSONDEbug(){
		$params = array(
			'contentID' =>		$this->contentID,
			'imageID' => 		$this->imageID ,
			'name'  => 			$this->name,
			'storageType' =>	$this->storageType,
		);

		return json_encode($params);
	}




	static function factoryDebug($contentWithJoin){
		$instance = new self(
			$contentWithJoin['image.name'],
			$contentWithJoin['image.storageType']
		);

		$instance->setID(
			$contentWithJoin['image.contentID'],
			$contentWithJoin['image.imageID']
		);

		return $instance;
	}


	function	toJSON(){
		$params = array(
			'contentID' =>		$this->contentID,
			'imageID' => 		$this->imageID ,
			'name'  => 			$this->name,
			'storageType' =>	$this->storageType,
		);

		return json_encode($params);
	}

	function	__construct($name, $storageType){
		$this->name = $name;
		$this->storageType = $storageType;
	}

	static function factory($contentWithJoin){
		$instance = new self(
			$contentWithJoin['image.name'],
			$contentWithJoin['image.storageType']
		);

		$instance->setID(
			$contentWithJoin['image.contentID'],
			$contentWithJoin['image.imageID']
		);

		return $instance;
	}

	function deleteClass(){
		deleteByType('image', $this->imageID);
	}

	function	getName(){
		return $this->name;
	}

	function getID(){
		return $this->imageID;
	}

	function	setID($contentID, $imageID){
		$this->contentID = $contentID;
		$this->imageID = $imageID;
	}

	function	display(){
		$contentDomain = getContentDomain($this->contentID);

		$proxyURL = $this->getContentURL();
		$thumbURL = $this->getContentThumbnail();

		$proxyURL = $contentDomain.$proxyURL;
		$thumbURL = $contentDomain.$thumbURL;

		$ID = "image_".$this->imageID;

		$output = "<a href='".$proxyURL."' target='_blank' class='clickableLink content contentImage' id='$ID' >";

		$output .= "<table class='contentImageWrapper' width='128px' height='128px' border='0' cellspacing='0' cellpadding='0px'><tr><td valign='middle'>";
		$output .= "<img src='".$thumbURL."' alt='An image' class='content contentImageThumbnail' />";

		$output .= "</td></tr></table>";
		$output .= "</a>";

		$dataBindingJS = "$('#".$ID."').data('serialized', '".$this->toJSON()."')";

		addJavascriptBodyLoadFunction($dataBindingJS);

		echo $output;
	}


	function	getStorageFolder(){
		return 'images/';
	}

	function	getContentURL(){
		return	"/proxy/".$this->imageID."/".$this->name;
	}

	function	getContentThumbnail(){
		return "/proxy/".$this->imageID."/thumbnail/".$this->name;
	}

	function getLocalCachedFilename($version = 'original'){

		$filename = $this->name;
		$pathInfo = pathinfo($filename);

		$fileExtension = "";

		if(array_key_exists('extension',$pathInfo) == TRUE){
			$fileExtension = $pathInfo['extension'];
		}

		return PATH_TO_ROOT."var/cache/images/".$version."/imageContent_".$this->contentID.".".$fileExtension;
	}
}







?>