<?php

abstract class gril{

    private $name;
    public function __construct(){
        $this->name = self::getName();
        echo $this->name;
    }
    public static function create(){
        return new static();
    }
    public function getName(){
        echo "mm";
    }
}

class bjgril extends gril{

}

class dbgril extends gril{
    public function getName(){
        return "gdmm";
    }
}


$g = dbgril::create();
