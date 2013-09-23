(function(){
//Auto-generated file by PHP-To-Javascript at Mon, 23 Sep 13 15:09:08 +0200
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file DefaultValue.php and then reconvert to make any changes


function getTotal (value1, value2  /*5*/){
		if(typeof value2 === "undefined"){
			value2 = 5;
		}

	return value1 + value2;
}

var test = function (){

	var mathTotal = getTotal(5);

	assert(mathTotal, 10);

	testEnd();
};
test();
})();
