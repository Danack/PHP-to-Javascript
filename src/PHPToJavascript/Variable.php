<?php


namespace PHPToJavascript;


class Variable {

    public $flags;

    public $name;
    
    public function __construct($name, $flags) {        
        $this->name = $name;
        $this->flags = $flags;
    }

    public function isPrivate() {
        return ($this->flags & DECLARATION_TYPE_PRIVATE);
    }
    
    public function isStatic() {
        return ($this->flags & DECLARATION_TYPE_STATIC);
    }
    
    public function getName() {
        return $this->name;   
    }
}

 