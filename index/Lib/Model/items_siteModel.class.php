<?php
class items_siteModel extends RelationModel
{
    protected $_link = array(
        'items' => array(
        	'mapping_type'  => HAS_MANY,
        	'class_name'    => 'items',
        	'foreign_key'   => 'cid',
        ),
    );
}