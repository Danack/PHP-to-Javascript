<?php

require_once(PATH_TO_ROOT."php_shared/objects/ContentFunctions.php");

//TODO - should only be called via entity class - so should be made member function
function deleteContent($type, $typeID){
	$object = getContentByClass($type, $typeID);
	$object->delete();
}

interface FactoryInterface {
	static public  function factory($contentWithJoin);
}


abstract class Content implements FactoryInterface{
	var $contentID;
	var $datestamp;

	abstract function display();

	abstract function getID();
	abstract function setID($contentID, $typeID);

	function	delete(){
		deleteByType('content', $this->contentID);
		deleteByType('contentTag', $this->contentID, 'content');
	}

	abstract function deleteClass();

	function	getStorageFolder(){
		return '';
	}

	function	getContentID(){
		return $this->contentID;
	}

	abstract function getContentURL();
	function	getName(){
		return '';
	}

	abstract function getContentThumbnail();

	function	displayThumbnail(){
		echo $this->getContentThumbnail();
	}

	function	addTag($tagType, $text = FALSE){
		addTag($this->contentID, $tagType, $text);
	}
}

//Circular dependencies if this is at the top....hmm
require_once(PATH_TO_ROOT."php_shared/objects/ContentFile.php");
require_once(PATH_TO_ROOT."php_shared/objects/ContentImage.php");
require_once(PATH_TO_ROOT."php_shared/objects/ContentLink.php");
require_once(PATH_TO_ROOT."php_shared/objects/ContentNote.php");
require_once(PATH_TO_ROOT."php_shared/objects/ContentQuote.php");
require_once(PATH_TO_ROOT."php_shared/objects/ContentTag.php");

?>