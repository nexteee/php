<?php

/**

* 判断域名是否已经注册 (会返回 coderbolg.com/coderbolg.net的注册情况)

* @param $domain 

* @param $ext 

* @return ARRAY

* @author zhuwc

* @version (2011-09-09)

* 

*/

$arr = array("b","c","d","f","g","h","j","k","l","m","p","q","r","s","t","v","w","x","y","z");
$arr2= array("a","e","i","o","u");
$arr_domain = array("com","cn","net","com.cn","cc","so","co","me","biz");

$i =0;
foreach($arr as $a){
    foreach($arr2 as $b){
        foreach($arr as $c){
                $name = $a.$b.$c;
                sleep(10);
                $arr = isRegister($name,$arr_domain);
                foreach($arr as $v){
                    echo $name.".".$v;
                    echo "\r\n";
                }
                $i++;
        }
    }
}


function isRegister($domain, $ext = array('com','net') ) {

    if ( empty($domain) ) {

        return false;

    }

    $post_data = $curl = $text = $return = array();

 foreach ($ext as $v) {

  $post_data[] = array('domain'=> $domain . '.'.$v );

 }

 $urls = array_fill(0, count($post_data) , 

 'http://pandavip.www.net.cn/check/check_ac1.cgi' );

    $handle = curl_multi_init();

    foreach($urls as $k => $v) {

        $curl[$k] = curl_init($v);

        curl_setopt($curl[$k], CURLOPT_HTTPHEADER, array(

 "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:6.0.2) Gecko/20100101 Firefox/6.0.2",

 "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",

 "Accept-Language: zh-cn,zh;q=0.5"

  ));
    $proxy = "222.184.9.243";
  //curl_setopt($curl[$k], CURLOPT_PROXY, $proxy);
  curl_setopt($curl[$k], CURLOPT_REFERER,'http://www.net.cn/');

        curl_setopt($curl[$k], CURLOPT_RETURNTRANSFER, 1);

  curl_setopt($curl[$k], CURLOPT_POST, 1);

  curl_setopt($curl[$k], CURLOPT_POSTFIELDS, $post_data[$k]);

        curl_multi_add_handle ($handle, $curl[$k]);

    }


    $active = null;
    sleep(20);

    do {

        $mrc = curl_multi_exec($handle, $active);

    } while ($mrc == CURLM_CALL_MULTI_PERFORM);


    while ($active && $mrc == CURLM_OK) {

        if (curl_multi_select($handle) != -1) {

            do {

                $mrc = curl_multi_exec($handle, $active);

            } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        }

    }


    foreach ($curl as $k => $v) {

        if (curl_error($curl[$k]) == "") {

        $text[$k] = (string) curl_multi_getcontent($curl[$k]);

        }

        curl_multi_remove_handle($handle, $curl[$k]);

        curl_close($curl[$k]);

    }

    curl_multi_close($handle);
    //print_r($text);
    foreach ($text as $key => $value) {


     if( false === $pos = strrpos($value,'|') ){

      $return[$key] = $ext[$key] . '|False'; 

     }else{

       if ( false === strrpos( substr( $value, $pos ), 'not' ) ){

        $return[$key] = $ext[$key] . '|Unregistered';

       }else{

        $return[$key] = $ext[$key] . '|Registered';

       }

     }

    }

    return $return;

}



?>
