//Auto-generated file by PHP-To-Javascript at Sat, 23 Feb 13 18:27:07 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file ArrayExample.php and then reconvert to make any changes


testVar = 3;

stringArray = {
	0 : 'Hello',
	1 : ' ',
	2 : 'world',
};

output = '';

for (var stringVal in stringArray) {		
                 var string = stringArray[stringVal];
	output +=  string;
}

assert(output, "Hello world");

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

function sumArray (intArray){

	var total = 0;

	for (var valueVal in intArray) {		
                 var value = intArray[valueVal];
		total += value;
	}

	return total;
}

//$value = sumArray($intArray);
value = sumArray(intArray['subArray']);

assert(value, 6);


