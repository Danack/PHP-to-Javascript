<?php


$closureFunction = function(){
    return 5;
};
    
assert($closureFunction(), 5);

//********************

$closureFunctionWithParam = function($x){
    return $x + 5;
};


assert($closureFunctionWithParam(3), 8);

//********************

$closureFunctionWithMultipleParam = function($x, $y, $z){
    return $x + $y + $z;
};

assert($closureFunctionWithParam(1, 2, 3), 6);


//********************


function testLocalClosure($y) {
 
    $localFunction = function ($x) {
        
        return $x + 2;
    };
    
    return $localFunction($y);
}


assert(testLocalClosure(2), 4);


testEnd();



