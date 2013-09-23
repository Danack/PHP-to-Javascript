//Auto-generated file by PHP-To-Javascript at Thu, 19 Sep 13 08:52:50 +0200
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file NameSpace.php and then reconvert to make any changes


/*namespace BaseReality*/

/*use Intahwebz\TestNamespace*/

trait SomeTrait {
	 var someVariable;
}


function TestClass() {

	
}



// inherit SomeTrait
// correct the constructor pointer because it points to SomeTrait
TestClass.prototype.constructor = TestClass;



function OtherClass() {


}



// inherit TestClass
// correct the constructor pointer because it points to TestClass
OtherClass.prototype.constructor = OtherClass;



//echo "Hello, This is in a namspace.";
//Todo - need some code that actually uses namespaces to test.
assert(1, 1);
testEnd();
