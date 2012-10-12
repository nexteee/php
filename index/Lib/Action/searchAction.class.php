<?php

class searchAction extends baseAction {

    public function index() {
        $keywords = isset($_REQUEST['keywords']) && trim($_REQUEST['keywords']) ? trim($_REQUEST['keywords']) :'';
        $sortby = isset($_REQUEST['sortby']) && trim($_REQUEST['sortby']) ? trim($_REQUEST['sortby']) : '';
        $type=empty($_REQUEST['type'])?'guang':$_REQUEST['type'];
        $this->assign('type',$type);
        
        $items_mod = D('items');
        
        import("ORG.Util.Page");
        
        $keywords = strip_tags($keywords);
        
        if($keywords!="") {
            $sql_where = "status='1' and title LIKE '%" . $keywords . "%'";
        }else{
            $sql_where = "status='1'";
        }
	switch ($sortby) {
            case 'dlikes' :
                $sql_order = "dlikes DESC,last_time DESC";
                break;
            case 'time' :
                $sql_order = "last_time DESC";
                break;
            case 'sort_order':
            	$sql_order = "sort_order DESC";
            	break;
            default :
                $sql_order = "dlikes DESC,last_time DESC";
                break;
        }
        $this->assign('search_keywords',explode(',',$this->setting['search_words']));
        $this->assign('keywords', $keywords);
        $this->assign('sortby', $sortby);
        $this->assign('items_total', $count);
        $count = $items_mod->where($sql_where)->count();
        $this->waterfall($count, $sql_where,$sql_order);
    }
}