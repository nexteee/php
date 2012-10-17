<?php


class d{
static $b=1;
function a()
{
    self::$b += 12;
    echo self::$b; 
    echo "\r\n";
}
function c()
{
    echo self::$b;
    self:$b = 77;
    echo self::$b;
    echo "\r\n";
}
}


$o = new d;

$o->a();
$o->a();


$o2 = new d;
//echo d::b;
$o2->c();
