<?php
return array(
    'URL_MODEL' => 2,
	'URL_ROUTER_ON' => true,
    'URL_ROUTE_RULES' => array(
        '/g-(\d+)/' => 'group/index?cid=:1',
        '/c-(\d+)/' => 'cate/index?cid=:1',
        '/item-(\d+).html/' => 'item/index?id=:1',

    ),
);
