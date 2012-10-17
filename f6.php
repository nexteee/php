<?php

class Person{
    function getName(){
        return "zxs";
    }

    function __tostring(){
        return "obj name:" . $this->getName();
        is_callable
    }
}


$p = new Person;
print $p;

