<?php

$str = file_get_contents("domain_ok.log");
$arr = explode("\n",$str);
$i = 0;
foreach ($arr as $v) {
    $i++;
    echo $v;
    echo "\t";
    if($i>6){
        echo "\r\n";
        $i = 0;
    }
}
