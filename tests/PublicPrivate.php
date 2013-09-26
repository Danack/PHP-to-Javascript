<?php

class SimplePrivate {
    private $foo = 4;

    public function getFoo(){
        return $this->foo;
    }
}

$f = new SimplePrivate();

$value = $f->getFoo();

assert($value, 4);

testEnd();

?>