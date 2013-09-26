
var testsRunForModule = 0;
var testsRunTotal = 0;
var testsPassedForModule = 0;
var testsPassedTotal = 0;

var testErrors = [];

function assert(var1, var2){
	testsRunForModule++;
	testsRunTotal++;

	if(var1 != var2){
		testErrors.push("Test " + testsRunForModule + "assert failed " + var1 + " != " + var2 );
        debugger;
	}
	else{
		testsPassedForModule++;
		testsPassedTotal++
	}
}

function assertGreater(var1, var2){
	testsRunForModule++;
	testsRunTotal++;

	if(!(var1 > var2)){
		testErrors.push("Test " + testsRunForModule + "assert failed !(" + var1 + " > " + var2 + ")");
	}
	else{
		testsPassedForModule++;
		testsPassedTotal++
	}
}


function	setTestsResult(domID){

	var string = "<span style='background-color: ff3f3f;'>No tests run</span>";

	if(testsRunForModule > 0){
		if(testsPassedForModule == testsRunForModule){
			string = "<span style='background-color: 2fcf2f;'>All tests passed " + testsPassedForModule  + " / " +   testsRunForModule+ "  </span>";
		}
		else if(testsPassedForModule > 0){
			string = "<span style='background-color: cf2f2f;'>Some tests passed " + testsPassedForModule + " / " + testsRunForModule  + "  </span>";
		}
		else{
			string = "<span style='background-color: cf2f2f;'>All tests failed</span>";
		}
	}


	for(var i in testErrors){
		var testError = testErrors[i];
		string = string + "<br/>" + testError;
	}

	document.getElementById(domID).innerHTML = string;

	testsRunForModule = 0;
	testsPassedForModule = 0;
	testErrors = [];
}


function	json_encode_object(objectToEncode, objectType){

	if(objectType === undefined){
		objectType = "UnknownObjectType";
	}

	var params = {};

	for(var name in objectToEncode){
		if(objectToEncode.hasOwnProperty(name)){

			var propertyValue = objectToEncode[name];

			if (propertyValue instanceof Function) {
				//skip it.
			}
			else{
				params[name] = propertyValue;
			}
		}
	}

	params.ObjectType = objectType;

	return JSON.stringify(params);
}

function json_decode(jsonString){
	return jQuery.parseJSON(jsonString);
}


// {{{ get_class
function get_class(obj) {
	// Returns the name of the class of an object
	//
	// +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_get_class/
	// +       version: 809.522
	// +   original by: Ates Goral (http://magnetiq.com)
	// +   improved by: David James
	// *     example 1: get_class(new (function MyClass() {}));
	// *     returns 1: "MyClass"
	// *     example 2: get_class({});
	// *     returns 2: "Object"
	// *     example 3: get_class([]);
	// *     returns 3: false
	// *     example 4: get_class(42);
	// *     returns 4: false
	// *     example 5: get_class(window);
	// *     returns 5: false
	// *     example 6: get_class(function MyFunction() {});
	// *     returns 6: false

	if (obj instanceof Object && !(obj instanceof Array)
		&& !(obj instanceof Function) && obj.constructor
		&& obj != window) {
		var arr = obj.constructor.toString().match(/function\s*(\w+)/);

		if (arr && arr.length == 2) {
			return arr[1];
		}
	}

	return false;
}// }}}

var runningTestID = null;

function testStart(testName){
	runningTestID = testName;
}

function testEnd(){
	if(runningTestID != null){
		document.getElementById(runningTestID).innerHTML = "Completed.";
		runningTestID = null;
	}
}
