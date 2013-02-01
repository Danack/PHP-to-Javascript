//Auto-generated file by PHP-To-Javascript at Fri, 01 Feb 13 16:31:12 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file ContentImage.php and then reconvert to make any changes



function ContentImage(name, storageType) {

			this.contentID;
			this.imageID;
			this.name;
			this.storageType;

	
		this.name = name;
		this.storageType = storageType;
	

	

	 

	

	

	

	

	


	

	

	

	

	
}

ContentImage.prototype.toJSON = function (){
		params = {
			'contentID'		:	this.contentID,
			'imageID'		: 	this.imageID,
			'name'			: 	this.name,
			'storageType'	:	this.storageType,
			'type'			:	'ContentImage'
		};

		return json_encode(params);
	}






ContentImage.factory = function (contentWithJoin){
		instance = new ContentImage(
			contentWithJoin['image.name'],
			contentWithJoin['image.storageType']
		);

		instance.setID(
			contentWithJoin['image.contentID'],
			contentWithJoin['image.imageID']
		);

		return instance;
	}





ContentImage.prototype.deleteClass = function (){
		deleteByType('image', this.imageID);
	}





ContentImage.prototype.getName = function (){
		return this.name;
	}





ContentImage.prototype.getID = function (){
		return this.imageID;
	}





ContentImage.prototype.setID = function (contentID, imageID){
		this.contentID = contentID;
		this.imageID = imageID;
	}





ContentImage.prototype.display = function (){
		contentDomain = getContentDomain(this.contentID);

		proxyURL = this.getContentURL();
		thumbURL = this.getContentThumbnail();

		proxyURL = contentDomain + "" + proxyURL;
		thumbURL = contentDomain + "" + thumbURL;

		ID = "image_" + "" + this.imageID;

		output = "<a href='" + "" + proxyURL + "" + "' target='_blank' class='clickableLink content' id='ID' >";

		output +=  "<table class='contentImageWrapper' width='128px' height='128px' border='0' cellspacing='0' cellpadding='0px'><tr><td valign='middle'>";
		output +=  "<img src='" + "" + thumbURL + "" + "' alt='An image' class='contentImageThumbnail' />";

		output +=  "</td></tr></table>";
		output +=  "</a>";

		dataBindingJS = "$('#" + "" + ID + "" + "').data('serialized', '" + "" + this.toJSON() + "" + "')";

		addJavascriptBodyLoadFunction(dataBindingJS);

		document.write( output);
	}





ContentImage.prototype.getStorageFolder = function (){
		return 'images/';
	}





ContentImage.prototype.getContentURL = function (){
		return	"/proxy/" + "" + this.imageID + "" + "/" + "" + this.name;
	}





ContentImage.prototype.getContentThumbnail = function (){
		return "/proxy/" + "" + this.imageID + "" + "/thumbnail/" + "" + this.name;
	}





ContentImage.prototype.getLocalCachedFilename = function (version  /*'original'*/){
		if(typeof version === "undefined"){
			version = 'original';
		}


		filename = this.name;
		pathInfo = pathinfo(filename);

		fileExtension = "";

		if(array_key_exists('extension',pathInfo) == TRUE){
			fileExtension = pathInfo['extension'];
		}

		return PATH_TO_ROOT + "" + "var/cache/images/" + "" + version + "" + "/imageContent_" + "" + this.contentID + "" + "." + "" + fileExtension;
	}





ContentImage.prototype.getPreview = function (){
		output = "<img src='/proxy/" + this.contentID + "/512/" + this.name + "' />";
		return output;
	}








