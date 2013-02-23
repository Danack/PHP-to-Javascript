//Auto-generated file by PHP-To-Javascript at Sat, 23 Feb 13 18:27:07 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file TraitExample.php  and then reconvert to make any changes



function JSONFactory(){

	 

	
}



JSONFactory.factory = function (jsonString){
		var data = json_decode(jsonString);

		var object = new this.prototype.constructor();

		for (var key in data) {
       var value = data[key];
			object[key] = value;
		}

		return object;
	}
JSONFactory.prototype.toJSON = function (){
		return json_encode_object(this);
	};



// Opening the require'TraitInclude.php';

//require_once('functions.php');



function ExampleJSON(objectID  /*false*/, name  /*'UnknownName'*/, value  /*"UnknownValue"*/){

	

	 		
	 		
	 		

	JSONFactory.call(this);

		if(typeof objectID === "undefined"){
			objectID = false;
		}

		if(typeof name === "undefined"){
			name = 'UnknownName';
		}

		if(typeof value === "undefined"){
			value = "UnknownValue";
		}

		this.objectID = objectID;
		this.name 	= name;
		this.value 	= value;
	

	
}



// inherit JSONFactory
ExampleJSON.prototype = new JSONFactory();
// correct the constructor pointer because it points to JSONFactory
ExampleJSON.prototype.constructor = ExampleJSON;
//Need to copy the static functions across and replace the parent class name with the child class name.
$.extend(ExampleJSON, JSONFactory);


ExampleJSON.prototype.objectID = null;
ExampleJSON.prototype.name = null;
ExampleJSON.prototype.value = null;


ExampleJSON.prototype.test = function (){
		return "name = " + "" + this.name + "" + " value = " + "" + this.value;
	};


testObject = new ExampleJSON(1, "First", "Testing");

json = testObject.toJSON();

duplicate = ExampleJSON.factory(json);

assert(duplicate.name == "First", true);
assert(duplicate.value == "Testing", true);


