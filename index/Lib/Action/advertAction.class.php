<?php

class advertAction extends baseAction {
    public function index() {
        $ad_mod = D('ad');
        $adboard_mod = D('adboard');
        $time_now = time();

        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : exit();
        //读取广告位信息
        $board_info = $adboard_mod->where('id=' . $id . ' AND status=1')->find();
        if (!$board_info) {
            return true;
        }
        $board_type_info = include_once(ROOT_PATH . '/data/adboard/' . $board_info['type'] . '.config.php');
        //读取版位下的广告
        if ($board_type_info['option']) {
            $ad_list = $ad_mod->where('board_id=' . $id . ' AND start_time<=' . $time_now . ' AND end_time>=' . $time_now . ' AND status=1')->order('ordid DESC')->select();
            $this->assign('ad_list', $ad_list);
            if (!$ad_list) {
                return true;
            }
        } else {
            $ad = $ad_mod->where('board_id=' . $id . ' AND start_time<=' . $time_now . ' AND end_time>=' . $time_now . ' AND status=1')->order('ordid DESC')->limit('0,1')->find();
            if (!$ad) {
                $ad = array(
                    'id' => '0',
                    'type' => 'image',
                    'code' => 'none.jpg',
                );
            }
            $this->assign('ad', $ad);
        }
        $this->assign('board_info', $board_info);
        $this->assign('board_type_info', $board_type_info);
        header("content-type:application/x-javascript");
        echo ($this->fetch(ROOT_PATH . '/data/adboard/' . $board_info['type'] . '.html'));
        exit;
    }
    public function click() {
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : '';

        if (!$id) {
            header('Location: ' . U('about/index', array('id' => 322, 'att' => 'ads')));
            exit;
        }
        $ad_mod = D('ad');
        $ad = $ad_mod->where('id=' . $id)->find();
        if (!$ad) {
            return false;
        }
        //点击数加一
        $new_clicks = $ad['clicks'] + 1;
        $ad_mod->where('id=' . $id)->save(array('clicks' => $new_clicks));
        header('Location: ' . $ad['url']);
    }
}

?>