<?php
class items_cateModel extends RelationModel
{
    protected $_link = array(
        'items' => array(
        	'mapping_type'  => HAS_MANY,
        	'class_name'    => 'items',
        	'foreign_key'   => 'cid',
        ),
        'items_tags' => array(
        	'mapping_type'  => MANY_TO_MANY,
        	'class_name'    => 'items_tags',
        	'foreign_key'   => 'cate_id',
        	'relation_foreign_key'=>'tag_id',
            'relation_table' => 'items_tags_cate',
            'auto_prefix' => true
        )
    );

    public function get_tags_ids($cate_id)
    {
        $list = $this->relation('items_tags')->where("id=".$cate_id)->find();
        $ids = array();
		foreach($list['items_tags'] as $tag)
		{
			$ids[] = $tag['id'];
		}
		return $ids;
    }

    public function get_cid_by_tags($tags)
    {
        $tags = array_unique($tags);
        $items_tags_mod = D('items_tags');
        foreach ($tags as $tag) {
            $re = $items_tags_mod->field(C('DB_PREFIX')."items_tags.id,".C('DB_PREFIX')."items_tags.name,itc.cate_id")->JOIN("LEFT JOIN ".C('DB_PREFIX')."items_tags_cate as itc ON itc.tag_id=".C('DB_PREFIX')."items_tags.id")->where(C('DB_PREFIX')."items_tags.name='".$tag."'")->find();
            if ($re) {
                return $re['cate_id'];
            }
        }
        return 0;
    }
    function get_list($id=0){
    	$items_cate_mod=D('items_cate');
    
    	$list=array();
    	$res=$items_cate_mod->where('pid='.$id)->select();
    	foreach($res as $key=>$val){
    		$val['level']=0;
    		$list[]=$val;
    		//二级分类
    		$arr=$items_cate_mod
    		->where('pid='.$val['id'])
    		->select();
    		//三级分类
    		foreach($arr as $k2=>$v2){
    			$v2['level']=1;
    			$v2['cls']="sub_".$val['id'];
    			$list[]=$v2;
    
    			$res3=$arr[$k2]['items']=$items_cate_mod->where('pid='.$v2['id'])
    			->select();
    			foreach($res3 as $k3=>$v3){
    				$v3['level']=2;
    				$v3['cls']="sub_".$val['id']." sub_".$v2['id'];
    				$list[]=$v3;
    			}
    		}
    		$res[$key]['items']=$arr;
    	}
    	return array('list'=>$res,'sort_list'=>$list);
    }
}