<?php
class membersAction extends baseAction
{
	function index()
	{
		$mod = D('members');
		//搜索
		$where = '1=1';
		if (isset($_GET['keyword']) && trim($_GET['keyword'])) {
		    $where .= " AND username LIKE '%".$_GET['keyword']."%'";
		    $this->assign('keyword', $_GET['keyword']);
		}
		if (isset($_GET['type']) && ($_GET['type']!='')) {
		    $type = intval($_GET['type']);
		    $where .= " AND type=$type";
		    $this->assign('type', $type);
		}

	    if(isset($_GET['sta']) && ($_GET['sta']!='')){
			$sta = intval($_GET['sta']);
			$where .= " AND status =$sta";
			$this->assign('sta', $_GET['sta']);
		}

		import("ORG.Util.Page");
		$count = $mod->where($where)->count();
		$p = new Page($count,20);

		$values = $mod->where($where)->limit($p->firstRow.','.$p->listRows)->order('lasttime DESC')->select();
		$key = 1;
		foreach($values as $k=>$val){
			$user_list[$k]['key'] = ($p->nowPage-1) * ($p->listRows) + $key++;
		}
		$page = $p->show();
		$this->assign('page',$page);
		$this->assign('user_list',$values);

		$this->display();
	}

	function edit()
    {
		$mod = D('members');
		if( isset($_GET['id']) ){
			$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error(L('please_select').L('user'));
		}
		$values = $mod->where('id='.$id)->find();
		$this->assign('member',$values);
		$this->assign('show_header', false);
		$this->display();
    }

    public function remark()
    {
        $members_mod = D('members');
	    $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error(L('please_select').L('user'));
        $member = $members_mod->field('id,remark')->where('id='.$id)->find();
        $this->assign('member', $member);
        $this->assign('show_header', false);
        $this->display();
    }

    public function remark_update()
    {
        $members_mod = D('members');
        $id = intval($_REQUEST['id']);
		if (false === $members_mod->create ()) {
			$this->error ( $members_mod->getError () );
		}
		// 更新数据
		$result = $members_mod->where('id = '.$id)->save();
		if(false !== $result){
			$this->success(L('operation_success'), U('members/index'), '', 'edit');
		}else{
			$this->error(L('operation_failure'));
		}
    }

    public function status_save()
    {
        $members_mod = D('members');
        $id = intval($_REQUEST['id']);
        $status = intval($_REQUEST['status']);
		$result = $members_mod->where('id = '.$id)->save(array('status'=>$status));
		if(false !== $result){
			echo '1';exit;
		}else{
			echo '0';exit;
		}
    }

	function delete()
	{
		$mod = D('members');
		if (!empty($_POST['id']) && isset($_POST['id']) && is_array($_POST['id'])) {
		    $ids = implode(',', $_POST['id']);
		    if(isset($_POST['dosubmit'])){
		    	$mod->delete($ids);
		    }else{
			    $ids = $_POST['id'];
		    	if(isset($_POST['checked'])){
				    $data['status'] = 1;
			    }
			    if(isset($_POST['modify'])){
				    $data['status'] = 2;
			    }
			    if(isset($_POST['pause'])){
				    $data['status'] = 3;
			    }
			    if(isset($_POST['refuse'])){
				    $data['status'] = 4;
			    }
			    for($i=0;$i<count($ids);$i++){
				    $data['id'] = $ids[$i];
				    $mod->save($data);
				}
		    }

		} else {
			$this->error('请选择要操作的会员！');
		}
		$this->success(L('operation_success'));
	}

	function modify($id,$type="status")
	{
		$mod = M('members');

		if(isset($_REQUEST['id']) && intval($_REQUEST['id'])){
			$id = intval($_REQUEST['id']);
		}

		$sql 	= "update ".C('DB_PREFIX')."members set $type=($type+1)%2 where id='$id'";
		$res 	= $mod->execute($sql);
		$values = $mod->where('id='.$id)->find();
		$msg 	= "<img src='./Public/images/status_".$values[$type].".gif'>";

		$data = array(
			'id'	=> $id,
			'msg'	=> $msg,
			'status'=> $values['status'],
		);
		echo  json_encode($data);
	}

	function update()
	{
		$mod = M('members');
		$id = isset($_POST['id']) && intval($_POST['id']) ? intval($_POST['id']) : $this->error(L('please_select').L('member'));
		$mod->create();
		$result = $mod->save();
		if(false !== $result){
			$this->success('更新成功', U('members/index'), '', 'edit');
		}else{
			$this->error('更新失败');
		}
	}

}
?>