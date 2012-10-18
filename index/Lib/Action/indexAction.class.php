<?php
class indexAction extends baseAction {
    function index() {
        $focus_mod = D('focus');
        $index_group_cates = $this->get_index_group_cates();
        //热门活动
        $article_mod = M('article');
        $top_actives = $article_mod->where('cate_id=9')->where('is_best=1 and status=1')->order('add_time DESC')->find();
        $this->assign('top_actives', $top_actives);
        $hot_actives = $article_mod->where('cate_id=9 and status=1')->limit('0,4')->order('is_hot DESC,add_time DESC')->select();
        $this->assign('hot_actives', $hot_actives);
        $ad_list = $focus_mod->where('cate_id=1 and status=1')->order('ordid DESC')->select();
        $this->assign('ad_list', $ad_list);
        $this->assign('index_group_cates', $index_group_cates);
        
        $this->waterfall(100,'','sort_order DESC,last_time DESC');
    }

    function get_index_group_cates() {
        $items_cate_mod = M('items_cate');
        $items=M("items");
        //查找需要显示的大分类
        $index_group_cates = $items_cate_mod->where("pid=0 AND is_hots=1 and status=1")->select();

        foreach ($index_group_cates as $key => $val) {
            //排序查找子分类
            //二级分类
            $cate2=$items_cate_mod
                ->where("pid=" . $val['id']." and status=1")->limit("0,10")
                ->order("ordid DESC")->select();
            $ids="-1";
            foreach($cate2 as $cate2_key=>$cate2_val){
                $ids.=','.$cate2_val['id'];
            }
            //三级分类
            $index_group_cates[$key]['s'] = $items_cate_mod
                ->where("pid in(".$ids.") AND is_hots=1 and status=1")->limit("0,10")
                ->order("ordid DESC")->select();
            //查找需要首页显示的子分类
            $g_result = $items_cate_mod
                ->where("pid in(" . $ids. ") AND is_hots=1 and status=1")
                ->order("ordid DESC")->select();
            foreach ($g_result as $gkey => $gval) {
                $g_result[$gkey]['items'] = $this->get_group_items($gval['id']);
            }
            //查询下面9个显示首页的商品图片
            $index_group_cates[$key]['g'] = $g_result;
        }
        return $index_group_cates;
    }
}