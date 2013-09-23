(function(){
//Auto-generated file by PHP-To-Javascript at Mon, 23 Sep 13 15:09:08 +0200
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file StaticTest.php and then reconvert to make any changes


function StaticTest() {

	 

	 

	 

	this.instanceMethod = function (){
		this.instanceVar++;
		//echo $this->instanceVar;
	};



	this.methodWithStatic = function (){
		 if (typeof this.methodWithStatic.staticVar == 'undefined')
 this.methodWithStatic.staticVar = 0;
		this.methodWithStatic.staticVar++;
		//echo $staticVar;
		return this.methodWithStatic.staticVar;
	};


}


StaticTest.prototype.instanceVar =   0;
StaticTest.staticClassVar =   0;

StaticTest.staticMethod = function (){
		StaticTest.staticClassVar++;
		//echo $this->staticClassVar;
	}


// TODO: BUG https://github.com/Danack/PHP-to-Javascript/issues/37
//$test = function(){

	StaticTest.staticMethod();


	var staticTest = new StaticTest();

	staticTest.instanceMethod();

	staticTest.methodWithStatic();
	staticTest.methodWithStatic();
	staticTest.methodWithStatic();

	assert(StaticTest.staticClassVar, 1);
	assert(staticTest.instanceVar, 1);
	assert(staticTest.methodWithStatic(), 4);

	testEnd();
//};
//$test();

})();
