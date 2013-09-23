<?php


$value = true ? 10 : 2 ;

assert($value, 10);



$ext = 0;
if (!$ext){
    
    $ext = 5;
}

assert($ext, 5);


testEnd();


 