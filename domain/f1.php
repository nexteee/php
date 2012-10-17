<?php
$arr = array("b","c","d","f","g","h","j","k","l","m","n","p","q","r","s","t","v","w","x","y","z","a","e","i","o","u");
//$arr2= array("a","e","i","o","u");
$arr_domain = array("com","cn","net","com.cn","cc","so","co","me","biz");

$i =0;
foreach($arr as $a){
    foreach($arr as $b){
        foreach($arr as $c){
            $str_domain = "";
            foreach($arr_domain as $domain){
                $str_domain .= $a.$b.$c.".".$domain.",";
                $i++;
            }
            echo check(rtrim($str_domain,","));
            sleep(5);
        }
    }
}


function check($str_domain){

    $url = "http://panda.www.net.cn/cgi-bin/check_muitl.cgi?domain=$str_domain";
    echo $url."\r\n";
    return file_get_contents($url);
}
