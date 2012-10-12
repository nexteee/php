<?php

class focusAction extends baseAction
{
	public function index()
	{
		$focus_mod = D('focus');
		$focus_cate_mod = D('focus_cate');

		//搜索
		$where = '1=1';
		if (isset($_GET['keyword']) && trim($_GET['keyword'])) {
			$where .= " AND title LIKE '%".$_GET['keyword']."%'";
			$this->assign('keyword', $_GET['keyword']);
		}
		if (isset($_GET['cate_id']) && intval($_GET['cate_id'])) {
			$where .= " AND cate_id=".$_GET['cate_id'];
			$this->assign('cate_id', $_GET['cate_id']);
		}
		import("ORG.Util.Page");
		$count = $focus_mod->where($where)->count();
		$p = new Page($count,20);
		$focus_list = $focus_mod->where($where)->limit($p->firstRow.','.$p->listRows)->order('ordid')->select();

		$key = 1;

		foreach($focus_list as $k=>$val){
			$focus_list[$k]['key'] =++$p->firstRow;
			$focus_list[$k]['cate_name'] = $focus_cate_mod->field('name')->where('id='.$val['cate_id'])->find();
		}
		$result = $focus_cate_mod->order('name')->select();
		$cate_list = array();
		foreach ($result as $val) {
			if ($val['pid']==0) {
				$cate_list['parent'][$val['id']] = $val;
			} else {
				$cate_list['sub'][$val['pid']][] = $val;
			}
		}

		$page = $p->show();
		$this->assign('page',$page);
		$this->assign('cate_list', $cate_list);
		$this->assign('focus_list',$focus_list);
		$this->display();
	}

	function edit()
	{
		$focus_mod = D('focus');
		if( isset($_GET['id']) ){
			$focus_id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error(L('please_select'));
		}
		$focus_cate_mod = D('focus_cate');
		$result = $focus_cate_mod->order('name')->select();
		$cate_list = array();
		foreach ($result as $val) {
			if ($val['pid']==0) {
				$cate_list['parent'][$val['id']] = $val;
			} else {
				$cate_list['sub'][$val['pid']][] = $val;
			}
		}
		$focus_info = $focus_mod->where('id='.$focus_id)->find();

		$this->assign('show_header', false);
		$this->assign('cate_list',$cate_list);
		$this->assign('focus',$focus_info);
		$this->display();

	}

	function update()
	{
		$focus_mod = D('focus');
		$data = $focus_mod->create();

		if ($_FILES['img']['name']!='') {
			$upload_list = $this->_upload();
			$data['img'] = $upload_list['0']['savename'];
			//删除老图片
			/*
			@unlink(ROOT_PATH."/data/items_cate/".$old_items_cate['img']);
			*/
		}
		//var_dump($data);
		//exit;
		$result = $focus_mod->save($data);
		if(false !== $result){
			$this->success(L('operation_success'),U('focus/index'));
		}else{
			$this->error(L('operation_failure'));
		}
	}

	function add()
	{
		$focus_cate_mod = D('focus_cate');
		$result = $focus_cate_mod->order('name')->select();
		$cate_list = array();
		foreach ($result as $val) {
			if ($val['pid']==0) {
				$cate_list['parent'][$val['id']] = $val;
			} else {
				$cate_list['sub'][$val['pid']][] = $val;
			}
		}
		$this->assign('cate_list',$cate_list);
		$this->display();
	}

	function insert()
	{
		$focus_mod = D('focus');

		if(false === $data = $focus_mod->create()){
			$this->error($focus_mod->error());
		}
		if ($_FILES['img']['name']!='') {
			$upload_list = $this->_upload();
			$data['img'] = $upload_list['0']['savename'];
		}
		$result = $focus_mod->add($data);
		if($result){
			$this->success(L('operation_success'));
		}else{
			$this->error(L('operation_failure'));
		}
	}

	function delete()
	{
		$focus_mod = D('focus');
		if((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
			$this->error('请选择...');
		}
		if( isset($_POST['id'])&&is_array($_POST['id']) ){
			$cate_ids = implode(',',$_POST['id']);
			/*
			 foreach($_POST['id'] as $val){
				@unlink(ROOT_PATH."/data/focus/".$focus_mod->where('id='.$val)->getField('img'));
				}
				*/
			$focus_mod->delete($cate_ids);
		}else{
			$cate_id = intval($_REQUEST['id']);
			/*
			 @unlink(ROOT_PATH."/data/focus/".$focus_mod->where('id='.$cate_id)->getField('img'));
            */
			$focus_mod->where('id='.$cate_id)->delete();
		}
		$this->success(L('operation_success'));
	}

	private function _upload()
	{
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize = 32922000;
		$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
		$upload->savePath = ROOT_PATH.'/data/focus/';
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

	function sort_order()
	{
		$focus_mod = D('focus');
		if (isset($_POST['listorders'])) {
			foreach ($_POST['listorders'] as $id=>$sort_order) {
				$data['ordid'] = $sort_order;
				$focus_mod->where('id='.$id)->save($data);
			}
			$this->success(L('operation_success'));
		}
		$this->error(L('operation_failure'));
	}

	public function status()
	{
		$id = intval($_REQUEST['id']);
		$type = trim($_REQUEST['type']);
		$focus_mod = D('focus');
		$res = $focus_mod->where('id=' . $id)->setField($type, array('exp', "(" . $type . "+1)%2"));
		$values = $focus_mod->where('id=' . $id)->getField($type);
		$this->ajaxReturn($values[$type]);
	}
}
?>