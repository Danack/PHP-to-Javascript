//Auto-generated file by PHP-To-Javascript at Fri, 20 Sep 13 16:23:58 +0200
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file Y:\dev\mostka\phpjs\tools\filewatcher\test\src\test.php and then reconvert to make any changes

/*T_INTERFACE FooInt {
	T_FUNCTION fooInt();
}}*/

 function FooAbs() {
	////abstract// //function// //fooAbsAbs//////

	this.fooAbs = function () {
		console.log( "--fooAbs--");
		for (var i = 0; i < 10; i++) {
			var e = (i * 10 - i)  + "" +  " foo in abs";
			console.log( e);
		}
	};


}




function Foo(fooCon) {
	 

	FooAbs.call(this);

		console.log( "--__construct--");
		this.foo = fooCon;
	

	this.fooInt = function () {
		console.log( "--fooInt--");
	};



	this.fooAbsAbs = function () {
		console.log( "--fooAbsAbs--");
	};



	this.getFoo = function () {
		console.log( "--getFoo--");
		return this.foo;
	};


}



// inherit FooAbs
Foo.prototype = new FooAbs();
// correct the constructor pointer because it points to FooAbs
Foo.prototype.constructor = Foo;
//Need to copy the static functions across and replace the parent class name with the child class name.
$.extend(Foo, FooAbs);
// inherit FooInt
Foo.prototype = new FooInt();
// correct the constructor pointer because it points to FooInt
Foo.prototype.constructor = Foo;
//Need to copy the static functions across and replace the parent class name with the child class name.
$.extend(Foo, FooInt);


Foo.prototype.foo =   "foo";





var foo = new Foo("foo2");
console.log( foo.getFoo());
foo.fooInt();
foo.fooAbsAbs();
foo.fooAbs();





function Foo3(){
	 var foo=5;

	this.foooo = function (){
		foo==6;
	};


}




function Foo5() {

	this.foooo = function (){
		this.foo=6;
		parent.foooo();
	};

Foo3.call(this);

}



// inherit Foo3
Foo5.prototype = new Foo3();
// correct the constructor pointer because it points to Foo3
Foo5.prototype.constructor = Foo5;
//Need to copy the static functions across and replace the parent class name with the child class name.
$.extend(Foo5, Foo3);




