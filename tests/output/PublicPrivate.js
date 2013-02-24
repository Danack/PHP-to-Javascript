//Auto-generated file by PHP-To-Javascript at Sun, 24 Feb 13 16:22:09 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file PublicPrivate.php and then reconvert to make any changes


function StaticTest() {

	 	
	 			

	  	
	  		var privateInstanceClassVar = 0;

	 

	
}


StaticTest.prototype.publicInstanceClassVar =   0;
StaticTest.publicStaticClassVar =   0;
StaticTest.privateStaticClassVar =   0;

StaticTest.staticMethod = function (){
		StaticTest.var publicStaticClassVar++;
		StaticTest.var privateStaticClassVar++;
	}
StaticTest.prototype.instanceMethod = function (){
		this.publicInstanceClassVar++;
		this.privateInstanceClassVar++;
	};


