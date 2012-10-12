<?php
class shop_cateAction extends baseAction{
	function index(){
		$shop_cate_mod=D('shop_cate');
        
		import("ORG.Util.Page");
		$count=$shop_cate_mod->order('sort_order')->count();
		$p=new page($count,20);

		$list=$shop_cate_mod->limit($p->firstRow.','.$p->listRows)->order('sort_order')->select();
		$key = 1;
		foreach($list as $k=>$val){
			$list[$k]['key'] =++$p->firstRow;
		}

		$this->assign('list',$list);
		$this->assign('page',$p->show());
		$this->display();
	}
	function add(){
		$this->assign('action','add');

		if (isset($_POST['dosubmit'])) {
			$shop_cate_mod=D('shop_cate');
			$data=$shop_cate_mod->create();
			$result=$shop_cate_mod->add($data);

			if($result){
				$this->success(L('operation_success'));
			}else{
				$this->error(L('operation_failure'));
			}
		}
		$this->display("info");
	}
	function edit(){
		$shop_cate_mod=D('shop_cate');

		$id=intval($_REQUEST['id']);

		$info=$shop_cate_mod->where("id=$id")->find();
		$this->assign('info',$info);
		$this->assign('action','edit');
        
		if (isset($_POST['dosubmit'])) {
			$data=$shop_cate_mod->create();
			$result=$shop_cate_mod->save($data);
			$this->success(L('operation_success'));
		}

		$this->display("info");
	}
}