//Auto-generated file by PHP-To-Javascript at Sun, 24 Feb 13 21:08:02 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file ClassExample.php and then reconvert to make any changes


function ClassExample(initialValue) {


			
	 	

	
		this.value = initialValue;

		this.testArray = {};
		this.testArray[0] = 1;
		this.testArray[1] = 2;
		this.testArray[2] = 3;
	

	

	

	
}


ClassExample.prototype.value =   null;
ClassExample.prototype.testArray =   null;


ClassExample.prototype.addValue = function (value){
		this.value += value;
	};


ClassExample.prototype.getArrayValue = function (){
		var result = 0;

		for (var testValueKey in this.testArray) {		
                 var testValue = this.testArray[testValueKey];
			result += testValue;
		}

		return result;
	};


ClassExample.prototype.getArrayValueWithIndex = function (){
		var result = 0;

		for (var key in this.testArray) {
       var testValue = this.testArray[key];
			result += testValue;
		}

		return result;
	};


classExample = new ClassExample(5);

classExample.addValue(5);

assert(classExample.value, 10);

assert(classExample.getArrayValue(), 6);

assert(classExample.getArrayValueWithIndex(), 6);


