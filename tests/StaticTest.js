//Auto-generated file by PHP-To-Javascript at Fri, 01 Feb 13 16:31:12 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file StaticTest.php and then reconvert to make any changes


function StaticTest(/*Constructor parameters here*/) {

	  var staticClassVar = 0;

	 this.instanceVar = 0;

	/*CONSTRUCTOR GOES HERE*/

	

	

}

StaticTest.prototype.staticMethod = function (){
		staticClassVar++;
		document.write( staticClassVar);
	}





StaticTest.prototype.instanceMethod = function (){
		this.instanceVar++;
		document.write( this.instanceVar);
	}





StaticTest.prototype.methodWithStatic = function (){
		 if (typeof methodWithStatic.staticVar == 'undefined')
 methodWithStatic.staticVar = 0;
		methodWithStatic.staticVar++;
		document.write( methodWithStatic.staticVar);
	}








