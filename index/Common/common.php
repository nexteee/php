<?php

function uc($url, $vars = '', $suffix = true, $redirect = false, $domain = false) {
    $uid = empty($_REQUEST['uid']) ? $_SESSION['user_id'] : intval($_REQUEST['uid']);
    if ($vars == '') {
        $vars = "&uid=" . $uid;
    }
    elseif (is_array($vars)) {
        $vars['uid'] = $uid;
    }
    return u($url, $vars, $suffix, $redirect, $domain);
}

function uimg($img) {
    if (empty($img)) {
        return SITE_ROOT . "data/user/avatar.gif";
    }
    return $img;
}

/*
 * 检查是否喜欢、分享,不存在则添加
 * */

function check_favorite($type, $id) {
    $mod = D($type);
    if (!$mod->where("items_id=$id and uid=" . $_SESSION['user_id'])->count() > 0) {
        $mod->add(array(
            'items_id' => $id,
            'add_time' => time(),
            'uid' => $_SESSION['user_id']
        ));
        return false;
    }
    return true;
}

/*
 * 获取喜欢记录
 * */

function get_favorite($type, $pagesize = 8) {
    import("ORG.Util.Page");

    if ($type == 'like_list') {
        $mod = D($type);
        $items_mod = D('items');

        $where = 'uid=' . $_SESSION['user_id'];

        $count = $mod->where($where)->count();
        $p = new Page($count, $pagesize);

        $like_list = $mod->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();

        foreach ($like_list as $key => $val) {

            $list[$key] = $items_mod->where('id=' . $val['items_id'])->find();
        }
        return array('list' => $list, 'page' => $p->show());
    }
    else if ($type == 'share_list') {
        $where = 'uid=' . $_SESSION['user_id'];
        $mod = D('items');
        $count = $mod->where($where)->count();

        $p = new Page($count, $pagesize);
        $list = $mod->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();
        return array('list' => $list, 'page' => $p->show());
    }
}
