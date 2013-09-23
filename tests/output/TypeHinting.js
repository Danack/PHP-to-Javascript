(function(){
//Auto-generated file by PHP-To-Javascript at Mon, 23 Sep 13 15:09:09 +0200
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file TypeHinting.php and then reconvert to make any changes



function sumArray (intArray){

	var total = 0;

	for (var valueKey in intArray) {
				if (!intArray.hasOwnProperty(valueKey)) continue;
				var value = intArray[valueKey];
		total += value;
	}

	return total;
}

function test1 (/*Form*/ form){
	return 1;
}


function test2 (/*array*/ array){
	return sumArray(array);
}


var test = function (){
	var test3 = test1(null);
	assert(test3, 1);



	var testArray = {0 : 1, 1 : 2, 2 : 3};
	var test4 = test2(testArray);
	assert(test4, 6);

	testEnd();
};
test();

})();
