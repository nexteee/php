<?php
class clientAction extends Action{
	private $admin_mod;
	private $items_cate_mod;
	private $result;
	function _initialize() {
		$this->admin_mod=D('admin');
		$this->items_cate_mod=D('items_cate');

		$user_name=$_REQUEST['user_name'];
		$password=md5(trim($_REQUEST['password']));
		$this->result=array();
		
		if($this->admin_mod->where("user_name='$user_name' and password='$password'")->count()==0){
			$this->result['error']=false;
			$this->result['msg']="账户不正确!";
			exit(json_encode($this->result));
		}else{
			$this->result['error']=true;
			$this->result['msg']="登录成功!";
		}
	}
	function index(){
		$this->result['content']=array(
            'items_cate'=>$this->items_cate_mod->field('id,name,pid')->select(),
		);
		exit(json_encode($this->result));
	}
	function upload(){
		$items_mod=D("items");
		$items_cate_mod=D("items_cate");
		$items_tags_mod=D("items_tags");
		$items_tags_item_mod=D("items_tags_item");
		
		$data=json_decode(urldecode($_REQUEST['data']));
		//var_dump($data);exit;
		foreach ($data as $item){
			if($item->cid==0&&false)
			{
				$item->cid=$items_cate_mod->get_cid_by_tags($tags);
				var_dump($tags)."\n";
				var_dump($item->id)."\n";
				var_dump($item->cid)."\n";
				var_dump($items_cate_mod->get_cid_by_tags($tags));exit;
			}
			if($items_mod->where("item_key='taobao_".$item->id."'")->count()>0)continue;
			$data=array(
			     'cid'=>$item->cid,
			     'item_key'=>"taobao_".$item->id,
			     'title'=>$item->title,
			     'img'=>$item->img.'_210x1000.jpg',
			     'simg'=>$item->img.'_64x64.jpg',
			     'bimg'=>$item->img,
			     'price'=>$item->price,
			     'url'=>$item->url,
			);
			$item_id=$items_mod->add($data);
			
			$tags = $items_tags_mod->get_tags_by_title($item->title);
			if ($tags) {
				$tags_arr = array_unique($tags);
				foreach ($tags_arr as $tag) {
					$tag_id = $items_tags_mod->where("name='" . $tag . "'")->getField('id');
					if ($tag_id) {
						$items_tags_mod->where('id=' . $isset_id)->setInc('item_nums');
					} else {
						$tag_id = $items_tags_mod->add(array('name' => $tag));
					}
					$items_tags_item_mod->add(array(
						'item_id' => $item_id,
						'tag_id' => $tag_id
					));
				}
			}
		}
		$this->result['error']=true;
		$this->result['msg']="上传成功!";
		exit(json_encode($this->result));
	}
}