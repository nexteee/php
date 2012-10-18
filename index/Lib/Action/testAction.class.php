<?php
class test extends Action{
	function index(){
		import("admin.Common.items_cate");
		$cate=new items_cate();
	}
}