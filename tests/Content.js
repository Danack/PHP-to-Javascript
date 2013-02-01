//Auto-generated file by PHP-To-Javascript at Fri, 01 Feb 13 16:31:12 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file Content.php and then reconvert to make any changes


//require_once(PATH_TO_ROOT + "" + "php_shared/objects/ContentFunctions.php");


//TODO - should only be called via entity class - so should be made member function


/*T_INTERFACE FactoryInterface {
	T_STATIC T_PUBLIC  T_FUNCTION factory(T_VARIABLE);
}}*/


 function Content(/*Constructor parameters here*/) /* FactoryInterface*/{
	 this.contentID;
	 this.datestamp;

	////abstract// //function// //display//////

	////abstract// //function// //getID//////
	////abstract// //function// //setID////$contentID//// //$typeID////

	/*CONSTRUCTOR GOES HERE*/

	////abstract// //function// //deleteClass//////

	

	

	////abstract// //function// //getContentURL//////
	

	////abstract// //function// //getContentThumbnail//////

	

	
}

Content.prototype.delete = function (){
		deleteByType('content', this.contentID);
		deleteByType('contentTag', this.contentID, 'content');
	}





Content.prototype.getStorageFolder = function (){
		return '';
	}





Content.prototype.getContentID = function (){
		return this.contentID;
	}





Content.prototype.getName = function (){
		return '';
	}





Content.prototype.displayThumbnail = function (){
		document.write( this.getContentThumbnail());
	}





Content.prototype.addTag = function (tagType, text  /*FALSE*/){
		if(typeof text === "undefined"){
			text = false;
		}

		addTag(this.contentID, tagType, text);
	}





//Circular dependencies if this is at the top....hmm
//require_once(PATH_TO_ROOT + "" + "php_shared/objects/ContentFile.php");
//require_once(PATH_TO_ROOT + "" + "php_shared/objects/ContentImage.php");
//require_once(PATH_TO_ROOT + "" + "php_shared/objects/ContentLink.php");
//require_once(PATH_TO_ROOT + "" + "php_shared/objects/ContentNote.php");
//require_once(PATH_TO_ROOT + "" + "php_shared/objects/ContentQuote.php");
//require_once(PATH_TO_ROOT + "" + "php_shared/objects/ContentTag.php");



function deleteContent (type, typeID){
	object = getContentByClass(type, typeID);
	object.delete();
}






