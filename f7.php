<?php

function aaa($b){
    echo "aaaaaaa".$b;
}

call_user_func('aaa');
if(is_callable("aaa")){
    call_user_func("aaa","aaa");
}else{
    echo "bbbb";
}
