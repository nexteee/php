<?php
$config = require("config.inc.php");
$array = array(
		'URL_MODEL' => 2,
		'URL_ROUTER_ON' => true,
		'URL_ROUTE_RULES' => array(
				'/g-(\d+)/' => 'group/index?cid=:1',
				'/c-(\d+)/' => 'cate/index?cid=:1',
				'/item-(\d+).html/' => 'item/index?id=:1',
		),
		'LOAD_EXT_CONFIG'=>'theme',
		/*
		'HTML_CACHE_ON'=>true,
		'HTML_CACHE_RULES'=>array(
				'index:'=>array('index','3600'),
		),
		*/
);
return array_merge($config, $array);