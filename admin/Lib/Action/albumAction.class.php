<?php
class albumAction extends baseAction{
	function index(){
		$album_mod=D('album');
		$where=" 1 ";
		if(isset($_REQUEST['keyword'])){
			$keys = $_REQUEST['keyword'];
			$where.=" and title like '%$keys%'";
		}
		if(!empty($_REQUEST['cate'])){
			$where.=" and cid=".intval($_REQUEST['cate']);
		}
		$select_list[]=array(
			'name'=>'cate',
			'items'=>$this->album_cate_mod->order("sort_order DESC")->select(),		
		);
		
		$count = $album_mod->count();
		$p =$this->pager($count);
		
		$res=$album_mod->where($where)->order("add_time DESC")->limit($p->firstRow.','.$p->listRows)->select();
		$list=$this->append_user($res);
		foreach($list as $key=>$val){
			$list[$key]['cate']=$this->album_cate_mod->where('id='.$val['cid'])->find();
		}
		$this->assign('select_list',$select_list);
		$this->assign('list',$list);
		$this->display();
	}
	function edit(){
		
	}
}