//Auto-generated file by PHP-To-Javascript at Sat, 23 Feb 13 18:27:07 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file ClassExample.php and then reconvert to make any changes


function ClassExample(initialValue) {


			

	
		this.value = initialValue;
	

	
}


ClassExample.prototype.value =   null;


ClassExample.prototype.addValue = function (value){
		this.value += value;
	};


classExample = new ClassExample(5);

classExample.addValue(5);

assert(classExample.value, 10);



