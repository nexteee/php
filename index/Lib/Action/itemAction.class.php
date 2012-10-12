<?php

class itemAction extends baseAction {

    public function index() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error("404");

        $items_mod = D('items');
        $items_pics_mod = D('items_pics');
        $items_cate_mod = D('items_cate');

        $item = $items_mod->where("id=".$id." and status='1'")->find();
        if( !$item ){
            $this->redirect('index/index');
        }
        $item['items_cate'] = $items_mod->relationGet("items_cate");
        $item['items_site'] = $items_mod->relationGet("items_site");
        $item['items_tags'] = $items_mod->relationGet("items_tags");
        $tag_str = '';
        foreach ($item['items_tags'] as $tag) {
            $tag_str .= $tag['name'] . ' ';
        }

        $this->seo['seo_title'] = !empty($item['seo_title']) ? $item['seo_title'] : $item['title'];
        $this->seo['seo_title'] = $this->seo['seo_title'] . ' - ' . $this->setting['site_name'];
        $this->seo['seo_keys'] = !empty($item['seo_keys']) ? $item['seo_keys'] : $tag_str;
        if($item['seo_title']=="") {
            $this->seo['seo_title'] = $item['title'];
        }else{
            $this->seo['seo_title'] = $item['seo_title'];
        }
        if($item['seo_keys']=="") {
            $this->seo['seo_keys'] = $tag_str." ".$item['title'];
        }else{
            $this->seo['seo_keys'] = $item['seo_keys'];
        }
        
        if($item['seo_desc']=="") {
            $this->seo['seo_desc'] = $item['title']." ".$item['info'];
        }else{
            $this->seo['seo_desc'] = $item['seo_desc'];
        }
        
        //同大类商品
        $siblings_cate_group = $items_cate_mod->where("pid=" . $item['items_cate']['pid'] . " AND is_hots=1")->limit('0,4')->order("ordid DESC")->select();
        foreach ($siblings_cate_group as $key => $val) {
            $siblings_cate_group[$key]['items'] = $this->get_group_items($val['id']);
        }
        $items_list = $items_mod->relation('items_site')->where('cid=' . $item['cid'])->limit('0,20')->select(); //同类商品
        $this_cate_group = $this->get_group_items($item['cid']); //所在分类展示
        $source_group = $this->get_group_items_bysource($item['sid']); //相同来源展示
        $items_mod->where('id=' . $id)->setInc('hits'); //浏览次

        $pics = $items_pics_mod->where("item_id=".$id)->order('add_time DESC')->select();

        $this->assign('items_id',$id);
        $this->assign('pics',$pics);
        $this->assign('seo', $this->seo);
        $this->assign('item', $item);
        $this->assign('items_list', $items_list);
        $this->assign('siblings_cate_group', $siblings_cate_group);
        $this->assign('this_cate_group', $this_cate_group);
        $this->assign('source_group', $source_group);

        $this->display();
    }

    function tao(){
    	$id=intval($_REQUEST['id']);
    	$res=$this->items_mod->where('id='.$id)->find();
    	if($res){
        	redirect($res['url']);
    	}
    }
    function img(){
    	$id = intval($_REQUEST['id']);
    	$type = $_REQUEST['type'];
    	$res = $this->items_mod->where('id='.$id)->find();
       	if($res){
           header("content-type:image/jpg");
           print_r(file_get_contents($res[$type]));
       }
    }

    public function submit_comment()
    {
        $data = 1;
        if($this->check_login()){
            $data = 0;
        }
        if( $data == 0 ){
            $item_id = $this->_get('item_id','intval');
            $info = $this->_get('info','trim');
            $item_comment_mod = D('items_comments');
            $items_mod = D('items');
            $comment['uid'] = $_SESSION['user_id'];
            $comment['item_id']=$item_id;
            $comment['info']=$info;
            $comment['add_time']=time();
            $comment['type']='album';
            $comment['status']=1;
            if( $item_comment_mod->add($comment) ){
                $items_mod->where('id='.$item_id)->setInc('comments');
                $data = 1;
            }else{
                $data = 0;
            }
        }
        echo $data;
    }
}