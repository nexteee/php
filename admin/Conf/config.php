<?php

if (!defined('THINK_PATH'))	exit();

$config = require("config.inc.php");

$array = array(
    'URL_MODEL' => 0,
    'DEFAULT_LANG' => 'zh-cn',
	//可忽略的权限检查
	'IGNORE_PRIV_LIST'=>array(
		array(
			'module_name'=>'admin',
			'action_list'=>array('ajax_check_username')
		),
		array(
			'module_name'=>'article',
			'action_list'=>array('delete_attatch')
		),	
		array(
			'module_name'=>'cache',
			'action_list'=>array('clearCache')
		),
		array(
			'module_name'=>'focus',
			'action_list'=>array('insert','update','status_save')
		),		
		array(
			'module_name'=>'index',
			'action_list'=>array()
		),		
		array(
			'module_name'=>'items',
			'action_list'=>array('collect_item')
		),	
		array(
			'module_name'=>'items_collect',
			'action_list'=>array('collect_success','taobao_collect_jump')
		),	
		array(
			'module_name'=>'items_tags_cate',
			'action_list'=>array('search',)
		),		
		array(
			'module_name'=>'public',
			'action_list'=>array()
		),
		array(
			'module_name'=>'role',
			'action_list'=>array('auth_submit')
		),		
		array(
			'module_name'=>'setting',
			'action_list'=>array('edit')
		),		
	),
	'TMPL_ACTION_ERROR'     => 'public:error',
    'TMPL_ACTION_SUCCESS'   => 'public:success',
);
return array_merge($config,$array);
?>