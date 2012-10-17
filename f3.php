<?php

class sample implements Iterator
{
    private $items = array(1,2,3,4,5,6,7);
    public $cur = "";
    public function rewind(){
        echo "rewind \r\n";
        $this->items = array(9,8,7);
        //reset($this->items);
    }

    public function current(){
        $var = current($this->items);
        $this->cur = $var;
        echo "current :$var \r\n";
        return $var;
    }

    public function key(){
        $var = key($this->items);
        echo "key:$var \r\n";
        //return $var;
    }

    public function next(){
        $var = next($this->items);
        echo "next:$var \r\n";
        return $var;
    }
    
    public function valid(){
        $var = $this->cur !== false;
        echo "valid:$var \r\n";
        return $var;
    }

}

$s = new sample;

print_r($s);
foreach($s as $k=>$v){
    print $k . "=>" .$v . "\r\n-----------\r\n";
}

print_r($s);
$s->rewind();
echo $s->current();
$s->next();
echo $s->current();
