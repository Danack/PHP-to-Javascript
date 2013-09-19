<?php


/**
 * Simple class that defines
 */
class CustomEvent{

	public $nullValue = NULL;

	private $privateValue = 12345;
	public $classValue = 12345;

	public static $stringValue = "basereality.previousPage";
	public static $intValue = 12345;
	public static $hexValue = 0xa12345;

	public static $noDefaultValue;

	public static $valueAfterComment = /* Seriously? */ 0xa12345;

	public static $valueCommentNewLine = // You're just trying to break it now.
		12345;

	public static $previewContent =  'basereality.previewContent';
	public static $closePreview =  'basereality.closePreview';
	public static $nextPage = 	'basereality.nextPage';
	public static $previousPage =  'basereality.previousPage';
	public static $firstPage =  "basereality.firstPage";
	public static $lastPage =  "basereality.lastPage";
}



assert(CustomEvent::$nextPage, 'basereality.nextPage');

assert(CustomEvent::$valueAfterComment, 10560325);


testEnd();
?>