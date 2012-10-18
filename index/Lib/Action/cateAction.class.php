<?php

class cateAction extends baseAction {

    public function index() {
        
        $cid = isset($_GET['cid']) && intval($_GET['cid']) ? intval($_GET['cid']) : 0;
        
        $items_mod = D('items');
        $items_cate_mod = D('items_cate');
        import("ORG.Util.Page");

        $cate_res = $this->items_cate_mod->order('ordid DESC')->where(array('status' => 1, 'id' => $cid))->find();
        $cate_res['level'] = 0;
        if (intval($cate_res['pid']) != 0) {
            $cate_res = $this->items_cate_mod->order('ordid DESC')->where(array('status' => 1, 'id' => $cate_res['pid']))->find();
            $cate_res['level'] = 1;
            if (intval($cate_res['pid']) != 0) {
                $cate_res = $this->items_cate_mod->order('ordid DESC')->where(array('status' => 1, 'id' => $cate_res['pid']))->find();
                $cate_res['level'] = 2;
            }
        }
        //print_r($cate_res);exit;
        $res = get_items_cate_list($cate_res['id'], $cate_res['level']);

        $this->assign('cate_list', $res['list']);

        
        if ($cid) {
            $cate_info = $items_cate_mod->order('ordid DESC')->where(array('status' => 1, 'id' => $cid))->find();
            if ('0' == $cate_info['pid']) {
                $pcid = $cid;
                $this->assign('pcate_info', $cate_info);
            }
            else {
                $scate = $items_cate_mod->order('ordid DESC')->where(array('status' => 1, 'id' => $cate_res['pid']))->select();
                $pcid = $cate_info['pid'];
                $pcate_info = $items_cate_mod->order('ordid DESC')->where(array('status' => 1, 'id' => $cate_res['pid']))->find();
                $this->assign('pcate_info', $pcate_info);
                $this->assign('cate_info', $cate_info);
            }
            $this->assign('scate', $scate);
            $this->seo['seo_title'] = !empty($cate_info['seo_title']) ? $cate_info['seo_title'] : $cate_info['name'];
            $this->seo['seo_title'] = $this->seo['seo_title'] . ' - ' . $this->setting['site_name'];
            $this->seo['seo_keys'] = !empty($cate_info['seo_keys']) ? $cate_info['seo_keys'] : $cate_info['name'];
            !empty($cate_info['seo_desc']) && $this->seo['seo_desc'] = $cate_info['seo_desc'];
        }
        
        $sql_where = "cid in(".$this->get_cat_ids($this->get_cat_tree($cid)).") and status=1 ";
        $count = $items_mod->where($sql_where)->count();
        $this->assign('count', $count);
        $this->assign('pcid', $pcid);
        $this->assign('cid', $cid);
        $this->assign('seo', $this->seo);

        $this->waterfall($count, $sql_where . ' AND status=1', 'sort_order DESC,id DESC');
    }

    public function tag() {
        $tag_name = isset($_GET['tag']) && trim($_GET['tag']) ? trim(urldecode($_GET['tag'])) : '';
        $p = !empty($_GET['p']) ? intval($_GET['p']) : 1;
        $sp = !empty($_GET['sp']) ? intval($_GET['sp']) : 1;
        $sp > 5 && exit;
        $list_rows = 200;
        $s_list_rows = 40;
        $show_sp = 0;

        $items_mod = D('items');
        $items_cate_mod = D('items_cate');
        $items_tags_mod = D('items_tags');
        import("ORG.Util.Page");
        $sql_where = "1=1";
        if ($tag_name) {
            $tag = $items_tags_mod->where("name='" . $tag_name . "'")->find();
            $sql_where = 'iti.tag_id=' . $tag['id'];
            $this->assign('tag', $tag);

            $this->seo['seo_title'] = !empty($tag['seo_title']) ? $tag['seo_title'] : $tag['name'];
            $this->seo['seo_title'] = $this->seo['seo_title'] . ' - ' . $this->setting['site_name'];
            $this->seo['seo_keys'] = !empty($tag['seo_keys']) ? $tag['seo_keys'] : $tag['name'];
            !empty($cate_info['seo_desc']) && $this->seo['seo_desc'] = $cate_info['seo_desc'];
        }

        //先计算大的分页
        $count = $items_mod->join("LEFT JOIN " . C('DB_PREFIX') . "items_tags_item as iti ON iti.item_id=" . C('DB_PREFIX') . "items.id")->where($sql_where)->count();
        $count > $s_list_rows && $show_sp = 1;
        $pager = new Page($count, $list_rows);
        $page = $pager->show_1();
        $first_row = $pager->firstRow + $s_list_rows * ($sp - 1);
        $items_list = $items_mod->relation('items_site')->join("LEFT JOIN " . C('DB_PREFIX') . "items_tags_item as iti ON iti.item_id=" . C('DB_PREFIX') . "items.id")->where($sql_where)->limit($first_row . ',' . $s_list_rows)->order('add_time DESC')->select();
        $this->assign('page', $page);
        $this->assign('p', $p);
        $this->assign('show_sp', $show_sp);
        $this->assign('sp', $sp);
        $this->assign('items_list', $items_list);

        //大分类
        $pcate = $items_cate_mod->where('pid=0')->select();
        $this->assign('pcate', $pcate);

        $this->assign('seo', $this->seo);
        $this->display();
    }

    public function like() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : '';
        $data = 0;
        if ($id) {
            $items_cate = M('items_cate');
            $data = $items_cate->where('id=' . $id)->setInc('item_likes');
        }
        $this->ajaxReturn($data);
    }

}