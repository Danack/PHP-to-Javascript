<?php





//Sent via email
$test = [0];
$test2 = array($test[0]);
$var3 = array('name' => 'gordon');




$x =  array($var3['name'  ]);


//Sent via email
define('BOOL_VAL', true);
define('INT_VAL', 5);
define('FLOAT_VAL', 0.5);
define('NULL_VAL', NULL);

assert(BOOL_VAL, true);
assert(INT_VAL * 2, 10);
assert(FLOAT_VAL + 1, 1.5);
assert(NULL_VAL, NULL);




//Sent via email
$boolTrue = true;
$boolFalse = !$boolTrue;
assert($boolFalse, false);



class InstanceTest{}

$instanceTest = new InstanceTest();

$correctInstance = false;

if ($instanceTest instanceof InstanceTest){
    $correctInstance = true;
}

assert($correctInstance, true);





//https://github.com/Danack/PHP-to-Javascript/issues/33

class ClassWithPrivate {
    private $foo = 4;

    function __construct(){
        //$this->foo += 2;
    }

    public function accessPrivate(){
        return $this->foo;
    }
}

$classWithPrivate = new ClassWithPrivate();
$classWithPrivate->accessPrivate();


assert($classWithPrivate->accessPrivate(), 4);



//https://github.com/Danack/PHP-to-Javascript/issues/39

class ClassWithPrivateProperty{

    private $foo = 0;

    function __construct(){
        $this->setFoo(5);
    }
    function setFoo($newFoo){
        $this->foo = $newFoo;
    }

    function getFoo(){
        return $this->foo;
    }
}


$classWithPrivateProperty = new ClassWithPrivateProperty();

assert($classWithPrivateProperty->getFoo(), 5);



//https://github.com/Danack/PHP-to-Javascript/issues/35

class Person {

    public $skillLevel = 0;

    function addSkill($skillLevel) {
        $this->$skillLevel += $skillLevel;
    }
}



$person1 = new Person();
$person1->addSkill(5);

$person2 = new Person();
$person2->addSkill(6);

assert($person1->skillLevel, 5);
assert($person2->skillLevel, 6);










// https://github.com/Danack/PHP-to-Javascript/issues/31

class Class1{
    public static $instance;
    public $foo = "foo";
}

class Class2{
    function __construct(){
        Class1::$instance->foo = 'foo';
    }
}


//https://github.com/Danack/PHP-to-Javascript/issues/45


//class ParentClass {
//    public $wth = "foo";
//
//
//    public function foo() {
//        return "parent";
//    }
//}
//
//class ChildClass extends ParentClass {
//    
//    static public $wth2 = "hmm";
//    
//    function __construct() {
//        echo parent::foo();
//        echo self::$wth2;
//    }
//}


//$test = new ChildClass();


class TestClass {
 
    public $message;
    
    function __construct($message) {
        $this->message = $message;
    }
    
    function windowCloseFunction() {
        echo "Goodbye ".$this->message;
    }

//    //If you have jQuery 
//    //JS window.onresize = $.proxy(this, 'windowCloseFunction');
    
    //Or with standard Javascript
//    //JS this.makeWindowCloseFunction = function($context, $functionName) {
//    //JS    return function() {
//    //JS        $functionName.call($context);
//    //JS    }
//    //JS}
//    //JS
//    //JS window.onresize = this.makeWindowCloseFunction(this, this.windowCloseFunction);
}

$test = new TestClass("cruel world!");






//https://github.com/Danack/PHP-to-Javascript/issues/44
//Embedded variables

$value1 = 5;

$value2 = "Hello " . $value1 . " there";
assert($value2, "Hello 5 there");

$value3 = "Hello ${value1} there";
assert($value3, "Hello 5 there");

function greet($name) {
    return "Hello ${name}!";
}

assert(greet("Bob"), "Hello Bob!");


//https://github.com/Danack/PHP-to-Javascript/issues/14
//Modulus doesn't work

$p2 = 8;
$step = 6;

$p2 -= ($p2 % $step);

//assert($p2, 6);

//*************************************************************
//*************************************************************

//https://github.com/Danack/PHP-to-Javascript/issues/15
$countValue = 4;
$countValue--;

//assert($countValue, 3);

$countValue--;

//assert($countValue, 2);


//https://github.com/Danack/PHP-to-Javascript/issues/48



function add($value1, $value2 = -1) {
    return $value1 + $value2;
}


//assert(add(5, 5), 10);

//assert(add(5), 4);



testEnd();



?>