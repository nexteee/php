<?php
 
echo 'example：';
if (function_exists("fastcgi_finish_request")) {
    fastcgi_finish_request();
    file_put_contents('log.txt', date('Y-m-d H:i:s') . " 上传视频\n", FILE_APPEND);
}else{
    echo "falied!";
} 
 
 
?>
