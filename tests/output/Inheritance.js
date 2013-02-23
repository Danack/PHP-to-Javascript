//Auto-generated file by PHP-To-Javascript at Sat, 23 Feb 13 18:27:07 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file Inheritance.php and then reconvert to make any changes


function Adult() {

			

	 

	 

	 

	
}


Adult.prototype.endOffset =   false;
Adult.prototype.adultClassVar = null;
Adult.prototype.isExtended =   false;
Adult.adultStaticVar = null;

Adult.prototype.adultValue = function (){
		this.adultClassVar = 123;
		return this.adultClassVar;
	};


function Child() {

	 

	 

	Adult.call(this);

		this.isExtended = true;
	

	
}



// inherit Adult
Child.prototype = new Adult();
// correct the constructor pointer because it points to Adult
Child.prototype.constructor = Child;
//Need to copy the static functions across and replace the parent class name with the child class name.
$.extend(Child, Adult);


Child.prototype.childInstanceVar = null;
Child.childStaticVar = null;


Child.prototype.childValue = function (){

	//	self::$childStaticVar = 123;

		this.childInstanceVar = 123;

		// TODO - Adult static variable needs to be accessed via Adult.adultStaticVar not
		// Child.adultStaticVar in the generated javascript. Which may be tricky.

	//	echo "This is a child method. \n" + $this->childInstanceVar + " " + self::$childStaticVar + " " +  + $this->adultClassVar + " " + self::$adultStaticVar;

		return 12345;
	};


adultOnly = new Adult();

assert(adultOnly.adultValue(), 123);
assert(adultOnly.isExtended, false);

child = new Child();

assert(child.childValue(), 12345);
assert(child.adultValue(), 123);
assert(child.isExtended, true);




