<?php
class groupAction extends baseAction{
	function index(){
		$cid = isset($_GET['cid']) && intval($_GET['cid']) ? intval($_GET['cid']) :0;
		$this->assign('cid',$cid);
		$this->assign('group_list',$this->get_group_list($cid));

		$this->display();
	}
	private function get_group_list($cid) {
		$items_cate_mod = M('items_cate');
		$items=M("items");
		//查找需要显示的大分类
		//$group_list= $items_cate_mod->where("pid=0")->select();
		if($cid==0){
			$group_list = $items_cate_mod->where("pid=0 AND is_hots=1")->select();
			foreach ($group_list as $key => $val) {
				//排序查找子分类
				$group_list[$key]['s'] = $items_cate_mod->where("pid=" . $val['id'])->limit("0,10")->order("ordid DESC")->select();
				//查找需要首页显示的子分类
				$g_result = $items_cate_mod->where("pid=" . $val['id'] . " AND is_hots=1")->order("ordid DESC")->select();
				foreach ($g_result as $gkey => $gval) {
					$g_result[$gkey]['items'] = $this->get_group_items($gval['id']);
				}
				//查询下面9个显示首页的商品图片
				$group_list[$key]['g'] = $g_result;
			}
		}else{
			$group_list=$items_cate_mod->where("id=$cid")->select();
			$group_list=$group_list['0'];
			$group_list['scat']= $items_cate_mod->where("pid=$cid")->order("ordid DESC")->select();
			//print_r($group_list['scat']);exit;
			$group_list['scat_num']=count($group_list['scat']);
			foreach ($group_list['scat'] as $gkey => $gval) {
				$group_items=$this->get_group_items($gval['id']);
					
				if(sizeof($group_items)>0){
					$group_list['scat'][$gkey]['items'] =$group_items;
				}else{
					unset($group_list['scat'][$gkey]);
				}
			}
		}
		//查询下面9个显示首页的商品图片
		//$group_list['g'] = $g_result;
		//print_r($group_list);exit;
		return $group_list;
	}
}