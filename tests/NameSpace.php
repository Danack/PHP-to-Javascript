<?php

namespace BaseReality;

use Intahwebz\TestNamespace;

trait SomeTrait{
	var $someVariable;
}



class TestClass{

	use Intahwebz\SomeTrait;

}

class OtherClass extends TestClass {


}

//echo "Hello, This is in a namspace.";

//Todo - need some code that actually uses namespaces to test.
assert(1, 1);


testEnd();
?>