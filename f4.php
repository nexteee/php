<?php
class myIterator implements Iterator
{
    private $p = 0;
    private $array = array("1 element","2 element","3 element");
    
    public function __construct()
    {
        $this->p = 0;
    }
    
    public function rewind()
    {
        echo "rewind \r\n";
        $this->p = 0;
    }

    public function current()
    {
        echo "current \r\n";
        return $this->array[$this->p];
    }
    
    public function key()
    {
        echo "key \r\n";
        return $this->p;
    }

    public function next()
    {
        echo "next \r\n";
        ++$this->p;
    }
    
    public function valid()
    {
        echo "valid \r\n";
        return isset($this->array[$this->p]);
    }
}

$it = new myIterator;
$times = 0;
foreach($it as $k=>$v){
    $times ++ ;
    echo "key:{$key} ,value:{$value} \r\n";
    echo "\r\n----the {$times} times travle finished!\n";
}
