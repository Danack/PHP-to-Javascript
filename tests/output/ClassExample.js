(function(){
//Auto-generated file by PHP-To-Javascript at Mon, 23 Sep 13 15:09:08 +0200
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
	


	this.testStatic = function (){
		var currentValue = ClassExample.staticVar;
		ClassExample.staticVar++;
		return currentValue;
	};



	this.addValue = function (value){
		this.value += value;
	};



	this.getArrayValue = function (){
		var result = 0;

		for (var testValueKey in this.testArray) {
				if (!this.testArray.hasOwnProperty(testValueKey)) continue;
				var testValue = this.testArray[testValueKey];
			result += testValue;
		}

		return result;
	};



	this.getArrayValueWithIndex = function (){
		var result = 0;

		for (var key in this.testArray) {
				if (!this.testArray.hasOwnProperty(key)) continue;
				var testValue = this.testArray[key];
			result += testValue;
		}

		return result;
	};



	 

	 this.privateFunction = function (){
		return this.foo;
	};



	 this.publicAccess = function (){
		return this.privateFunction();
	};



	 this.testFunctionStatic = function (){
		 if (typeof this.testFunctionStatic.countUnique == 'undefined')
 this.testFunctionStatic.countUnique = 0;
		this.testFunctionStatic.countUnique++;

		return this.testFunctionStatic.countUnique;
	};



	 
	 this.privateField = function (){
		return this.privateFieldVal;
	};



	 /* publicArr */;
}


ClassExample.prototype.value =   null;
ClassExample.prototype.testArray =   null;
ClassExample.prototype.publicVal5 =   5;
ClassExample.prototype.foo =   "foo";
ClassExample.prototype.privateFieldVal = 'foo';
ClassExample.prototype.publicArr = {'a':5};
ClassExample.staticVar =   0;











var test = function (){

	var classExample = new ClassExample(5);

	classExample.addValue(5);

	assert(classExample.value, 10);

	assert(classExample.getArrayValue(), 6);

	assert(classExample.getArrayValueWithIndex(), 6);

	assert(classExample.publicVal5, 5);

	assert(classExample.publicArr['a'], 5);


	classExample.testStatic();
	classExample.testStatic();
	var result = classExample.testStatic();


	//Called two times, but value is only incremented twice
	assert(result, 2);
	/*  In js is impossible make private fields or methods
	$privateAccessed = false;
	$exceptionCaught = false;
	try{
		//Yes IDE - I know this isn't allowed.
		///** @noinspection PhpIllegalArrayKeyTypeInspection * /

		// @SuppressWarnings
		$classExample->privateFunction();
		$privateAccessed = true;
	}
	catch(Exception $error){
		//This correct - the private function should not be callable.
		$exceptionCaught = true;
	}

	assert($privateAccessed, false);
	assert($exceptionCaught, true);
	*/
	var value = classExample.publicAccess();
	assert(value, 'foo');
	value = classExample.privateField();
	assert(value, 'foo');


	classExample.testFunctionStatic();
	classExample.testFunctionStatic();
	classExample.testFunctionStatic();
	value = classExample.testFunctionStatic();

	assert(value, 4);

	testEnd();

};
test();
})();
