<?php
class items_tagsModel extends RelationModel
{
	protected $_link = array(
        'items_cate' => array(
        	'mapping_type'  => MANY_TO_MANY,
        	'class_name'    => 'items_cate',
        	'foreign_key'   => 'tag_id',
        	'relation_foreign_key'=>'cate_id',
            'relation_table' => 'items_tags_cate',
            'auto_prefix' => true
        )
    );


    public function get_tags_by_title($title)
    {
        vendor('pscws4.pscws4', '', '.class.php');
        $pscws = new PSCWS4();
		$pscws->set_dict(ROOT_PATH.'/includes/scws/dict.utf8.xdb');
		$pscws->set_rule(ROOT_PATH.'/includes/scws/rules.utf8.ini');
		$pscws->set_ignore(true);
		$pscws->send_text($title);
		$words = $pscws->get_tops(10);
		$tags = array();
		foreach ($words as $val) {
		    $tags[] = $val['word'];
		}
		$pscws->close();
		return $tags;
    }
}