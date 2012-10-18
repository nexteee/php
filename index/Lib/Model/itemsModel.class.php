<?php
class itemsModel extends RelationModel
{
	protected $_link = array(
        'items_cate' => array(
            'mapping_type'  => BELONGS_TO,
            'class_name'    => 'items_cate',
            'foreign_key'   => 'cid',
		),
        'items_site' => array(
            'mapping_type'  => BELONGS_TO,
            'class_name'    => 'items_site',
            'foreign_key'   => 'sid',
	),
        'items_tags' => array(
        	'mapping_type'  => MANY_TO_MANY,
        	'class_name'    => 'items_tags',
        	'foreign_key'   => 'item_id',
        	'relation_foreign_key'=>'tag_id',
                'relation_table'=>'items_tags_item',
                'auto_prefix' => true
	),
        'user'=>array(
            'mapping_type'  => BELONGS_TO,
            'class_name'    => 'user',
            'foreign_key'   => 'uid',
		),
	);
	//删除分享
	function del($id){
		$mod=D('items');
		$cate_mod=D('items_cate');
		$res=$mod->where("id=$id and uid=".$_SESSION['user_id'])->find();
		if($res){
			$cate_mod->where('id='.$res['cid'])->setDec('item_nums');
			return $mod->where("id=$id and uid=".$_SESSION['user_id'])->delete();
		}

	}
}