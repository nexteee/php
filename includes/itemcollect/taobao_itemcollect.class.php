<?php

class taobao_itemcollect {

	public function fetch($url) {
		$id = $this->get_id($url);
		if (!$id) {
			return false;
		}
		$key = 'taobao_' . $id;
		$setting_mod = M('setting');
		$map['name'] = array(array('eq', 'taobao_usernick'), array('eq', 'taobao_pid'), array('eq', 'taobao_appkey'), array('eq', 'taobao_appsecret'), 'or');
		$setting = $setting_mod->where($map)->select();
		foreach ($setting as $val) {
			$taoke_setting[$val['name']] = $val['data'];
		}
		vendor('Taobaotop.TopClient');
		vendor('Taobaotop.RequestCheckUtil');
		vendor('Taobaotop.Logger');
		$tb_top = new TopClient;
		$tb_top->appkey = $taoke_setting['taobao_appkey'];
		$tb_top->secretKey = $taoke_setting['taobao_appsecret'];
		$req = $tb_top->load_api('ItemGetRequest');
		$req->setFields("detail_url,title,nick,pic_url,price,volume");
		$req->setNumIid($id);
		$resp = $tb_top->execute($req);
		$res=(array)$resp;		
		if($res['code']){
			if($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest'){
			    exit(json_encode(array('data'=>$res))); 		
			}else{
				exit($res['msg']);
			}
		}

		if (!isset($resp->item)) {
			return false;
		}

		$item = (array) $resp->item;
		$result = array();
		$result['item']['key'] = $key;
		$result['item']['title'] = $item['title'];
		$result['item']['price'] = $item['price'];
		$result['item']['img'] = $item['pic_url'] . '_210x1000.jpg';
		$result['item']['simg'] = $item['pic_url'] . '_64x64.jpg';
		$result['item']['bimg'] = $item['pic_url'];
		$result['item']['url'] = $item['detail_url'];
                $result['item']['volume'] = $item['volume'];
		$result['item']['author'] = 'taobao';
               
		$shop_click_url = '';
		if ($taoke_setting['taobao_pid']) {
			$req = $tb_top->load_api('TaobaokeItemsDetailGetRequest');
			$req->setFields("click_url");
			$req->setNumIids($id);
			$req->setPid($taoke_setting['taobao_pid']);
			$resp = $tb_top->execute($req);

			if (isset($resp->taobaoke_item_details)) {
				$taoke = (array) $resp->taobaoke_item_details->taobaoke_item_detail;
				if ($taoke['click_url']) {
					$result['item']['url'] = $taoke['click_url'];
				}
			}
		}
		return $result;
	}

	public function get_id($url) {
		$id = 0;
		$parse = parse_url($url);
		if (isset($parse['query'])) {
			parse_str($parse['query'], $params);
			if (isset($params['id'])) {
				$id = $params['id'];
			} elseif (isset($params['item_id'])) {
				$id = $params['item_id'];
			} elseif (isset($params['default_item_id'])) {
				$id = $params['default_item_id'];
			}
		}
		return $id;
	}

	public function get_key($url) {
		$id = $this->get_id($url);
		return 'taobao_' . $id;
	}

}

?>