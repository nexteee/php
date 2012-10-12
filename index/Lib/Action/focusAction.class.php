<?php

class focusAction extends baseAction {
	public function index() {		
		$focus_mod = D('focus');
		$focus_cate_mod = D('focus_cate');
		$time_now = time();

		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : exit();
		//读取广告位信息
		$focus_cate_info = $focus_cate_mod->where('id=' . $id)->find();
		if (!$focus_cate_info) {
			return true;
		}
		$board_type_info = include_once(ROOT_PATH . "/data/focus/$focus.config.php");
		//读取版位下的广告

		$ad_list = $focus_mod->where('cate_id=' . $id . ' AND status=1')->order('ordid DESC')->select();
		$this->assign('ad_list', $ad_list);		
		if (!$ad_list) {
			return true;
		}

		$this->assign('focus_cate_info', $focus_cate_info);
		$this->assign('board_type_info', $board_type_info);
		$this->display(ROOT_PATH . '/data/focus/focus.html');
	}
	public function click() {
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : '';

		$focus_mod = D('focus');
		$ad = $focus_mod->where('id=' . $id)->find();
		if (!$ad) {
			return false;
		}
		//点击数加一
		$new_clicks = $ad['clicks'] + 1;
		$focus_mod->where('id=' . $id)->save(array('clicks' => $new_clicks));
		header('Location: ' . $ad['url']);
	}
}

?>