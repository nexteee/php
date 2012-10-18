<?php
class shopAction extends baseAction{
	function index(){
		$shop_mod=D('shop');

		import("ORG.Util.Page");
		$count=$shop_mod->order('sort_order')->count();
		$p=new page($count,20);

		$list=$shop_mod->limit($p->firstRow.','.$p->listRows)->order('sort_order')->select();
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
			$shop_mod=D('shop');
			$data=$shop_mod->create();

			if ($_FILES['img']['name'] != '') {
				$upload_list=$this->_upload($_FILES['img']);
				$data['img'] = $upload_list['0']['savename'];
			}

			$result=$shop_mod->add($data);

			if($result){
				$this->success(L('operation_success'));
			}else{
				$this->error(L('operation_failure'));
			}
		}
		$this->assign('cate_list',$this->get_cate_list());
		$this->display("info");
	}
	function edit(){
		$shop_mod=D('shop');

		$id=intval($_REQUEST['id']);

		$info=$shop_mod->where("id=$id")->find();
		$this->assign('info',$info);
		$this->assign('action','edit');


		if (isset($_POST['dosubmit'])) {
			$data=$shop_mod->create();

			if ($_FILES['img']['name'] != '') {
				$upload_list=$this->_upload($_FILES['img']);
				$data['img'] = $upload_list['0']['savename'];
			}

			$result=$shop_mod->save($data);
			$this->success(L('operation_success'));
		}
		$this->assign('cate_list',$this->get_cate_list());
		$this->display("info");
	}

	private function _upload(){
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize = 32922000;
		$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
		$upload->savePath = ROOT_PATH.'/data/shop/';
		$upload->saveRule = uniqid;

		if (!$upload->upload()) {
			//捕获上传异常
			$this->error($upload->getErrorMsg());
		} else {
			//取得成功上传的文件信息
			$uploadList = $upload->getUploadFileInfo();
		}
		return $uploadList;
	}
	private function get_cate_list(){
		$shop_cate_mod=D('shop_cate');
		return $shop_cate_mod->select();
	}
}