<?php

$str = file_get_contents("word2.log");

$arr = explode("\n",$str);
$arr_domain = array("com","cn","net","com.cn","cc","so","co","me","biz");


foreach($arr as $v){
    if(trim($v) != "" && strlen(trim($v))<7 && strlen(trim($v))>2 ){
        $arr2[] = $v;
    }
}

foreach($arr2 as $name){
    $str_domain = "";
    foreach($arr_domain as $domain){
        $str_domain .= $name.".".$domain.",";
    }
    $str_domain = rtrim($str_domain,",");
    echo check($str_domain);
    sleep(7);
}

function check($str_domain){
         
    $url = "http://panda.www.net.cn/cgi-bin/check_muitl.cgi?domain=$str_domain";
    echo $url."\r\n";
    return file_get_contents($url);
}

