<?php
class album_cateAction extends baseAction{
	function index(){
		$where=" 1 ";
		if(isset($_REQUEST['keyword'])){
			$keys = $_REQUEST['keyword'];
			$where.=" and title like '%$keys%'";
		}
		
		$count = $this->album_cate_mod->count();
		$p =$this->pager($count);
		
		$res=$this->album_cate_mod->where($where)->order("id desc")
			->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('list',$res);		
		$this->display();
	}
	function add(){
		if(!empty($_REQUEST['dosubmit'])){
			$data = $this->album_cate_mod->create();
			$count = $this->album_cate_mod->where("title='".trim($data['title'])."'")->count();
			if($count>0){
				$this->success(L('分类名称已经存在!'));exit;
			}
			$data['add_time'] = time();
			$res = $this->album_cate_mod->add($data);
			$this->check_res($res);
		}
		$this->display('edit');
	}
	function edit(){
		if(!empty($_REQUEST['dosubmit'])){
			$data = $this->album_cate_mod->create();
			$count = $this->album_cate_mod->where("id !=".$_REQUEST['id']." and title='".trim($_REQUEST['title'])."'")->count();
			if($count>0){
                            $this->success(L('分类名称已经存在!'));exit;
			}
			$this->album_cate_mod->save($data);
			$this->success(L('operation_success'));
		}
		$info=$this->album_cate_mod->where('id='.$_REQUEST['id'])->find();
		$this->assign('info',$info);
		$this->display('edit');
	}
}