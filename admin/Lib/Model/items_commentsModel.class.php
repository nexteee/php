<?php
class items_commentsModel extends RelationModel{
	protected $_link = array(
	   'user'=>array(
	        'mapping_type'  => BELONGS_TO,
            'class_name'    => 'user',
            'foreign_key'   => 'uid',
	   ),
	);
	/*
	 * 获取评论
	 * */
	function get_items_comments($items_id,$pagesize=8){
		import("ORG.Util.Page");

		$mod=D('items_comments');
		$where="items_id=$items_id";

		$count = $mod->where($where)->count();
		$p = new Page($count,$pagesize);
        //print_r($p->show_1());exit;
		$list=$mod->relation('user')->where($where)->order("id desc")->limit($p->firstRow.','.$p->listRows)->select();
		return array('list'=>$list,'page'=>$p->show_1(),'count'=>$count);
	}

}