<?php

class items_tagsAction extends baseAction
{

    function index()
    {
        $items_tags_mod = D('items_tags');

        //搜索
        $where = '1=1';
        $keyword = isset($_GET['keyword']) && trim($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $order = isset($_GET['order']) && trim($_GET['order']) ? trim($_GET['order']) : '';
        $sort = isset($_GET['sort']) && trim($_GET['sort']) ? trim($_GET['sort']) : 'desc';
        if ($keyword) {
            $where .= " AND name LIKE '%".$keyword."%'";
            $this->assign('keyword', $keyword);
        }
        //排序
        $order_str = 'id desc';
        if ($order) {
            $order_str = $order . ' ' . $sort;
        }

        import("ORG.Util.Page");
        $count = $items_tags_mod->where($where)->count();
        $p = new Page($count,40);
        $tags_list = $items_tags_mod->where($where)->relation('items_cate')->limit($p->firstRow.','.$p->listRows)->order($order_str)->select();
        $key = 1;
        foreach($tags_list as $k=>$val){
            $tags_list[$k]['key'] = ++$p->firstRow;
        }
        $big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=items_tags&a=add\', title:\'添加标签\', width:\'500\', height:\'300\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加标签');
        $page = $p->show();
        $this->assign('page',$page);
        $this->assign('big_menu',$big_menu);
        $this->assign('tags_list',$tags_list);
        $this->assign('order',$order);
        if ($sort == 'desc') {
            $sort = 'asc';
        } else {
            $sort = 'desc';
        }
        $this->assign('sort',$sort);
        $this->display();
    }

    function add()
    {
        if(isset($_POST['dosubmit'])){
            $items_tags_mod    = M('items_tags');
            $data     = array();
            $name     = isset($_POST['name']) && trim($_POST['name']) ? trim($_POST['name']) : $this->error(L('input').'标签名称');
            $exist    = $items_tags_mod->where("name='".name."'")->count();
            if($exist != 0){
                $this->error('该标签已经存在');
            }
            $data = $items_tags_mod->create();
            $items_tags_mod->add($data);
            $this->success(L('operation_success'), '', '', 'add');

        }else{
            $this->assign('show_header', false);
            $this->display();
        }
    }

    function edit()
    {
        if(isset($_POST['dosubmit'])){
            $items_tags_mod     = M('items_tags');
            $data     = $items_tags_mod->create();
            $result = $items_tags_mod->where("id=".$data['id'])->save($data);
            if(false !== $result){
                $this->success(L('operation_success'), '', '', 'edit');
            }else{
                $this->error(L('operation_failure'));
            }
        }else{
            $items_tags_mod = M('items_tags');
            $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择...');
            $tags = $items_tags_mod->where('id='.$id)->find();
            $this->assign('tags',$tags);
            $this->assign('show_header', false);
            $this->display();
        }
    }

    function delete()
    {
        $items_tags_mod = M('items_tags');
        if((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择...');
        }
        if( isset($_POST['id'])&&is_array($_POST['id']) ){
            $ids = implode(',',$_POST['id']);
            $items_tags_mod->delete($ids);
        }else{
            $id = intval($_GET['id']);
            $items_tags_mod->where('id='.$id)->delete();
        }
        $this->success(L('operation_success'));
    }

    //修改状态
    function status()
    {
        $items_tags_mod = M('items_tags');
        $id     = intval($_REQUEST['id']);
        $type     = trim($_REQUEST['type']);
        $sql     = "update ".C('DB_PREFIX')."items_tags set $type=($type+1)%2 where id='$id'";
        $res     = $items_tags_mod->execute($sql);
        $values = $items_tags_mod->where('id='.$id)->find();
        $this->ajaxReturn($values[$type]);
    }
}
?>