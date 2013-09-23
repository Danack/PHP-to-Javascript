(function(){
//Auto-generated file by PHP-To-Javascript at Mon, 23 Sep 13 15:09:08 +0200
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file SimpleExample.php and then reconvert to make any changes


var test = function (){
	var target = "world";

	var greeting = "Hello " + target + "!";

	assert(greeting, "Hello world!");

	var total = 0;

	for (var i=1 ; i<=5 ; i++){
		total += i;
	}

	assert(total, 15);

	var value = '123';
	var delta = 123;
	value = +value + delta;

	assert(value, 246);

	var result = str_pad(value, 6, '0', 'STR_PAD_LEFT');

	assert(result, '000246');


	var testArray = {
		0 : 1, 1 : 2, 2 : 3
	};

	function testFunction (testArray){

		result = 0;

		for (var testKey in testArray) {
				if (!testArray.hasOwnProperty(testKey)) continue;
				var test = testArray[testKey];
			result += test;
		}

		return result;
	}

	value = testFunction(testArray);

	assert(value, 6);

	//****************************************
	//$globalVar1 = 1;

	function testGlobal (){
		
		var localVar = var globalVar1 + 2;
		return localVar;
	};

	assert(testGlobal(), 3);

	assert(eval('typeof Function.prototype.toString'), "function");

	var testGlobal= function (){
		
	};
	var strFunc = testGlobal.toString();
	assert(strFunc.indexOf('global') || strFunc.indexOf('foo1') || strFunc.indexOf('foo2') || strFunc.indexOf(';'), -1);

	// TODO: BUG https://github.com/Danack/PHP-to-Javascript/issues/36
	/*$testGlobal2= function(){
		global $foo1,$foo2,$foo4,$foo5,$foo6,$window;
		$foo3 = $foo2;
		$foo3 = eval($foo4);
		$foo3 = $foo4 + $foo5;
		$foo3 = $window;
		return $foo6;
	};
	assert($testGlobal2(),5);*/

	testEnd();
};
test();

})();
