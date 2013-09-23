//Auto-generated file by PHP-To-Javascript at Thu, 19 Sep 13 08:52:50 +0200
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file TraitExample.php  and then reconvert to make any changes



trait JSONFactory {

	 function factory (jsonString) {
		var data = json_decode(jsonString);
		var object = new this.prototype.constructor();
		for (var key in data) {
				if (!data.hasOwnProperty(key)) continue;
				var value = data[key];
			object[key] = value;
		}
		return object;
	}

	function toJSON () {
		var className = get_class(this);
		return json_encode_object(this, className);
	}
}

testEnd();


// Opening the require'TraitInclude.php';
//require_once('functions.php');
function ExampleJSON(objectID  /*false*/, name  /*'UnknownName'*/, value  /*"UnknownValue"*/) {

	

	 
	 
	 

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
		this.name     = name;
		this.value    = value;
	

	this.test = function () {
		return "name = "  + "" +  this.name  + "" +  " value = "  + "" +  this.value;
	};


}



// inherit JSONFactory
// correct the constructor pointer because it points to JSONFactory
ExampleJSON.prototype.constructor = ExampleJSON;


ExampleJSON.prototype.objectID = null;
ExampleJSON.prototype.name = null;
ExampleJSON.prototype.value = null;



var testObject = new ExampleJSON(1, "First", "Testing");
var json = testObject.toJSON();
var duplicate = ExampleJSON.factory(json);
assert(duplicate.name == "First", true);
assert(duplicate.value == "Testing", true);
testEnd();

