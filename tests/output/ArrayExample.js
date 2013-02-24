//Auto-generated file by PHP-To-Javascript at Sun, 24 Feb 13 21:08:02 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file ArrayExample.php and then reconvert to make any changes



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
                 var value = intArray[valueKey];
		total += value;
	}

	return total;
}

//*************************************************************
//*************************************************************

//Shamoan

pushArray = {};

array_push(pushArray, 1);
array_push(pushArray, 2);
array_push(pushArray, 3);

value = sumArray(pushArray);

assert(value, 6);

//*************************************************************
//*************************************************************

testVar = 3;

stringArray = {
	0 : 'Hello',
	1 : ' ',
	2 : 'world',
};

output = '';

for (var stringKey in stringArray) {		
                 var string = stringArray[stringKey];
	output +=  string;
}

assert(output, "Hello world");

//*************************************************************
//*************************************************************

intArray = {
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


