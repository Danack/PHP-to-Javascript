//Auto-generated file by PHP-To-Javascript at Sun, 24 Feb 13 21:08:02 +1100
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

mathTotal = getTotal(5);

assert(mathTotal, 10);

