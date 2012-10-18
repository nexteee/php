<?php

class items_tags_cateAction extends baseAction
{

	public function index()
	{
	    $items_cate_mod = D('items_cate');
		$items_tags_mod = D('items_tags');
		$items_tags_cate_mod = D('items_tags_cate');

		$cate_id = isset($_GET['cate_id']) && intval($_GET['cate_id']) ? intval($_GET['cate_id']) : '';

		//分类信息
		$cate_info = $items_cate_mod->where('id='.$cate_id)->find();
		$pcate_info = $items_cate_mod->where('id='.$cate_info['pid'])->find();

		//搜索
		$where = "itc.cate_id=".$cate_id;
		if (isset($_GET['keyword']) && trim($_GET['keyword'])) {
		    $where .= " AND it.name LIKE '%".$_GET['keyword']."%'";
		    $this->assign('keyword', $_GET['keyword']);
		}

		import("ORG.Util.Page");
		$count = $items_tags_cate_mod->where('cate_id='.$cate_id)->count();
		$p = new Page($count,40);
		//$tags_list = $items_tags_cate_mod->field('it.name,it.id,itc.*')->join("LEFT JOIN ".C('DB_PREFIX')."items_tags as it ON it.id=".C('DB_PREFIX')."items_tags_cate.tag_id")->where($where)->limit($p->firstRow.','.$p->listRows)->select();
		$tags_list = M()->query("SELECT itc.*,it.name,it.id FROM ".C('DB_PREFIX')."items_tags_cate as itc LEFT JOIN ".C('DB_PREFIX')."items_tags as it ON it.id=itc.tag_id WHERE ".$where." LIMIT ".$p->firstRow.",".$p->listRows);
		
		$key = 1;
		foreach($tags_list as $k=>$val){
			$tags_list[$k]['key'] = ++$p->firstRow;
		}
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=items_tags_cate&a=add&cate_id='.$cate_id.'\', title:\'添加标签 - '.$pcate_info['name'].' &gt; '.$cate_info['name'].'\', width:\'750\', height:\'430\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加标签');
		$page = $p->show();
		$this->assign('page', $page);
    	$this->assign('big_menu', $big_menu);
		$this->assign('tags_list', $tags_list);
		$this->assign('cate_id', $cate_id);
		$this->assign('cate_info', $cate_info);
		$this->assign('pcate_info', $pcate_info);
		$this->display();
	}

	public function search()
	{
	    $items_cate_mod = D('items_cate');
	    $items_tags_mod = D('items_tags');
	    $keywords = isset($_GET['keywords']) && trim($_GET['keywords']) ? trim($_GET['keywords']) : '';
	    $cate_id = isset($_GET['cate_id']) && intval($_GET['cate_id']) ? intval($_GET['cate_id']) : '';
	    $where = '1=1';
	    if ($keywords) {
	        $where .= " AND name LIKE '%".$keywords."%'";
	    }
	    if ($cate_id) {
	        $noids = $items_cate_mod->get_tags_ids($cate_id);
	        if ($noids) {
	            $where .= " AND id NOT IN (".implode(',',$noids).")";
	        }
	    }
	    $data = $items_tags_mod->where($where)->limit('0,60')->select();
	    $this->ajaxReturn($data);
	}

	function add()
	{
	    if (isset($_POST['dosubmit'])) {
	        $cate_id = isset($_POST['cate_id']) && intval($_POST['cate_id']) ? intval($_POST['cate_id']) : $this->error('参数错误');
    	    $tag_ids 	= isset($_POST['tag_ids']) && trim($_POST['tag_ids']) ? trim($_POST['tag_ids']) : '';
    	    $custom_tags =  isset($_POST['custom_tags']) && trim($_POST['custom_tags']) ? trim($_POST['custom_tags']) : '';
    	    $tag_ids_arr = array();
    	    if ($tag_ids) {
    	        $tag_ids = substr($tag_ids,1);
    	        $tag_ids_arr = explode('|', $tag_ids);
    	    }
    	    if ($custom_tags) {
    	        $items_tags_mod = D('items_tags');
    	        $custom_tags_arr = explode(',', $custom_tags);
    	        foreach ($custom_tags_arr as $val) {
    	            $tag_id = $items_tags_mod->where("name='".$val."'")->getField('id');
    	            if (!$tag_id) {
    	                $tag_id = $items_tags_mod->add(array('name' => $val,));
    	            }
    	            if ($tag_id) {
    	                $tag_ids_arr[] = $tag_id;
    	            }
    	        }
    	    }
    	    $items_tags_cate_mod = D('items_tags_cate');
    	    foreach ($tag_ids_arr as $val) {
    	        $items_tags_cate_mod->add(array(
    	            'cate_id' => $cate_id,
    	            'tag_id' =>$val
    	        ));
    	    }
    	    $this->success(L('operation_success'), '', '', 'add');
	    }
	    $cate_id = isset($_GET['cate_id']) && intval($_GET['cate_id']) ? intval($_GET['cate_id']) : '';
	    $items_cate_mod = D('items_cate');
		$cate_info = $items_cate_mod->where('id='.$cate_id)->find();
		$pcate_info = $items_cate_mod->where('id='.$cate_info['pid'])->find();
	    $this->assign('cate_id', $cate_id);
	    $this->assign('cate_info', $cate_info);
	    $this->assign('pcate_info', $pcate_info);
		$this->display();
	}

	function del()
    {
		$items_tags_cate_mod = D('items_tags_cate');
		$cate_id = isset($_REQUEST['cate_id']) && intval($_REQUEST['cate_id']) ? intval($_REQUEST['cate_id']) : $this->error('请选择分类');
		if((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择需要删除的标签');
		}
		if( isset($_POST['id'])&&is_array($_POST['id']) ){
			$ids = implode(',',$_POST['id']);
			foreach ($ids as $id) {
			    $items_tags_cate_mod->where('tag_id='.$id.' AND cate_id='.$cate_id)->delete();
			}
		}else{
			$id = intval($_GET['id']);
		    $items_tags_cate_mod->where('tag_id='.$id.' AND cate_id='.$cate_id)->delete();
		}
		$this->success(L('operation_success'));
    }
}
?>