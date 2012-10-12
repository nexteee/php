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
    );
}