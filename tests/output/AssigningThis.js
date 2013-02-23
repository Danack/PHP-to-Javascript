//Auto-generated file by PHP-To-Javascript at Sat, 23 Feb 13 18:27:07 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file AssigningThis.php and then reconvert to make any changes


function paramTest (object, otherVar){
	return object;
}


function TestClass(){

	 

	

	

	

	

	


}


TestClass.prototype.five =   5;

TestClass.prototype.getThis = function (){
		return this;
	};


TestClass.prototype.getThis2 = function (){
		return paramTest(this, 'ignored var');
	};


TestClass.prototype.getThis3 = function (){
		var returnValue = this;
		return returnValue;
	};


TestClass.prototype.getClassName = function (){
		var className = get_class(this);
		return className;
	};


TestClass.prototype.getValue = function (){
		return this.five;
	};


testClass = new TestClass();

assert(testClass.getThis(), testClass);
assert(testClass.getThis2(), testClass);
assert(testClass.getThis3(), testClass);
assert(testClass.getClassName(), 'TestClass');
assert(testClass.getValue(), 5);


