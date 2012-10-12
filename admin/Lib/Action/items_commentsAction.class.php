<?php

class flinkAction extends baseAction {
        function index() {
		$comments_mod = M('uc_comments');
		import("ORG.Util.Page");
		$prex = C('DB_PREFIX');

		//搜索
		$where = '1=1';
		if (isset($_GET['keyword']) && trim($_GET['keyword'])) {
			$where .= " AND (" . $prex . "items.title LIKE '%" . $_GET['keyword'] . "%' or uc_comments.info LIKE '%" . $_GET['keyword'] . "%')";
			$this->assign('keyword', $_GET['keyword']);
		}
		
		$count = $comments_mod->where($where)->count();
		$p = new Page($count, 20);
		$lists = $comments_mod->where($where)->field($prex . 'uc_comments.*,' . $prex . 'items.title as title')->join('LEFT JOIN ' . $prex . 'items ON ' . $prex . 'uc_comments.pid = ' . $prex . 'items.id ')->limit($p->firstRow . ',' . $p->listRows)->order($prex . 'uc_comments.add_time DESC')->select();
                echo $comments_mod->getlastSQL();exit();
                
		$key = 1;
		foreach ($lists as $k => $val) {
			$lists[$k]['key'] = ++$p->firstRow;
		}

		//$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=flink&a=add\', title:\'' . L('add_flink') . '\', width:\'450\', height:\'250\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('add_flink'));
		$page = $p->show();
		$this->assign('page', $page);
		//$this->assign('big_menu', $big_menu);
		$this->assign('lists', $lists);
		$this->display();
	}
    
	function index2() {
		$items_mod = M('items');
		$items_comments_mod = M('items_comments');
		import("ORG.Util.Page");
		$prex = C('DB_PREFIX');

		//搜索
		$where = '1=1';
		if (isset($_GET['keyword']) && trim($_GET['keyword'])) {
			$where .= " AND (" . $prex . "info.name LIKE '%" . $_GET['keyword'] . "%' or title LIKE '%" . $_GET['keyword'] . "%')";
			$this->assign('keyword', $_GET['keyword']);
		}

		$count = $mod->where($where)->count();
		$p = new Page($count, 20);
		$list = $mod->where($where)->field($prex . 'user_comments.*,' . $prex . 'items.title as title')->join('LEFT JOIN ' . $prex . 'items ON ' . $prex . 'user_comments.items_id = ' . $prex . 'items.id ')->limit($p->firstRow . ',' . $p->listRows)->order($prex . 'user_comments.add_time DESC')->select();
                
                echo $mod->getlastSQL();exit();
		$key = 1;
		foreach ($list as $k => $val) {
			$list[$k]['key'] = ++$p->firstRow;
		}

		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=items_comments&a=add\', title:\'' . L('add_flink') . '\', width:\'450\', height:\'250\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('add_flink'));
		$page = $p->show();
		$this->assign('page', $page);
		$this->assign('big_menu', $big_menu);
		$this->assign('list', $list);
		$this->display();
	}

	function add() {
		if (isset($_POST['dosubmit'])) {

			$flink_mod = M('flink');
			$data = array();
			$name = isset($_POST['name']) && trim($_POST['name']) ? trim($_POST['name']) : $this->error(L('input') . L('flink_name'));
			$url = isset($_POST['url']) && trim($_POST['url']) ? trim($_POST['url']) : $this->error(L('input') . L('flink_url'));
			$exist = $flink_mod->where("url='" . $url . "'")->count();
			if ($exist != 0) {
				$this->error('该链接已经存在');
			}
			$data = $flink_mod->create();

			if ($_FILES['img']['name'] != '') {
				$upload_list=$this->_upload($_FILES['img']);
				$data['img'] = $upload_list['0']['savename'];
			}

			$flink_mod->add($data);
			$this->success(L('operation_success'), '', '', 'add');
		} else {
			$flink_cate_mod = D('flink_cate');
			$flink_cate_list = $flink_cate_mod->select();
			$this->assign('flink_cate_list', $flink_cate_list);

			$this->assign('show_header', false);
			$this->display();
		}
	}

	function edit() {
		if (isset($_POST['dosubmit'])) {
			$flink_mod = M('flink');
			$data = $flink_mod->create();

			if ($_FILES['img']['name'] != '') {
				$upload_list=$this->_upload($_FILES['img']);
				$data['img'] = $upload_list['0']['savename'];
			}

			$result = $flink_mod->where("id=" . $data['id'])->save($data);
			if (false !== $result) {
				$this->success(L('operation_success'), '', '', 'edit');
			} else {
				$this->error(L('operation_failure'));
			}
		} else {
			$flink_mod = M('flink');
			if (isset($_GET['id'])) {
				$flink_id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的链接');
			}
			$flink_cate_mod = D('flink_cate');
			$flink_cate_list = $flink_cate_mod->select();
			$this->assign('flink_cate_list', $flink_cate_list);

			$flink_info = $flink_mod->where('id=' . $flink_id)->find();
			$this->assign('flink_info', $flink_info);
			$this->assign('show_header', false);
			$this->display();
		}
	}

	function del() {
		$flink_mod = M('flink');
		if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
			$this->error('请选择要删除的链接！');
		}
		if (isset($_POST['id']) && is_array($_POST['id'])) {
			$flink_ids = implode(',', $_POST['id']);
			$flink_mod->delete($flink_ids);
		} else {
			$flink_id = intval($_GET['id']);
			$flink_mod->where('id=' . $flink_id)->delete();
		}
		$this->success(L('operation_success'));
	}

	function ordid() {
		$flink_mod = D('flink');
		if (isset($_POST['listorders'])) {
			foreach ($_POST['listorders'] as $id => $sort_order) {
				$data['ordid'] = $sort_order;
				$flink_mod->where('id=' . $id)->save($data);
			}
			$this->success(L('operation_success'));
		}
		$this->error(L('operation_failure'));
	}

	//修改状态
	function status() {
		$flink_mod = D('flink');
		$id = intval($_REQUEST['id']);
		$type = trim($_REQUEST['type']);
		$sql = "update " . C('DB_PREFIX') . "flink set $type=($type+1)%2 where id='$id'";
		$res = $flink_mod->execute($sql);
		$values = $flink_mod->where('id=' . $id)->find();
		$this->ajaxReturn($values[$type]);
	}

	private function _upload() {
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize = 3292200;
		//$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
		$upload->savePath = ROOT_PATH.'/data/flink/';

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

}

?>