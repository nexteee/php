<?php
/*
$d = opendir("/Users/zhuxiaosheng/code/test");
while($f = readdir($d)){
    echo $f."\r\n";
}
*/

$path = RecursiveDirectoryIterator("/Users/zhuxiaosheng/code/test");
