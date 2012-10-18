<?php
function uimg($img){
	if(empty($img)){
		return SITE_ROOT."data/user/avatar.gif";
	}
	return $img;
}