(function(){
//Auto-generated file by PHP-To-Javascript at Mon, 23 Sep 13 15:09:08 +0200
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file ArrayExample.php and then reconvert to make any changes


var test = function (){

	var dataMap = {
		0 : {0 : 'photoID', 1 : 'id'},
		1 : {0 : 'owner', 1 : 'owner'},
	};

	var gah = {0 : 1, 1 : 2};


	test = gah[0];

	var intArray2 = {
		0 : 1,
		1 : 2,
		2 : 3,
		'subArray' : {
			0 : {0 : 1},
			1 : 2,
			3 : 3
		},
	};


	 function	array_push_value(array, value){
	
	 	for(var x=0 ; x<1000 ; x++){
	 		if(array.hasOwnProperty(x) == false){
	 			array[x] = value;
	 			return;
	 		}
	 	}
	
	 	throw new Error("Can't push onto array - it is too large.");
	 }


	function sumArray (intArray){

		var total = 0;

		for (var valueKey in intArray) {
				if (!intArray.hasOwnProperty(valueKey)) continue;
				var value = intArray[valueKey];
			total += value;
		}

		return total;
	}

	//*************************************************************
	//*************************************************************

	//Shamoan

	var pushArray = {};

	array_push(pushArray, 1);
	array_push(pushArray, 2);
	array_push(pushArray, 3);

	var value = sumArray(pushArray);

	assert(value, 6);

	//*************************************************************
	//*************************************************************

	var testVar = 3;

	var stringArray = {
		0 : 'Hello',
		1 : ' ',
		2 : 'world',
	};

	var output = '';

	for (var stringKey in stringArray) {
				if (!stringArray.hasOwnProperty(stringKey)) continue;
				var string = stringArray[stringKey];
		output +=  string;
	}

	assert(output, "Hello world");

	//*************************************************************
	//*************************************************************

	var intArray = {
		0 : 1,
		1 : 2,
		2 : 3,
		'subArray' : {
			0 : 1,
			1 : 2,
			3 : 3
		},
	};



	//$value = sumArray($intArray);
	value = sumArray(intArray['subArray']);

	assert(value, 6);

	//*************************************************************
	//*************************************************************

	function ArrayTestClass(){

		 

		this.getTestArray = function (){
			var params = {'noteID' : this.noteID};
			return params;
		};


	}


ArrayTestClass.prototype.noteID =   123;


var arrayTestClass = new ArrayTestClass();

	var testArray = arrayTestClass.getTestArray();

	value = sumArray(testArray);

	assert(value, 123);
	console.log(5);

	testEnd();
};
test();
})();
