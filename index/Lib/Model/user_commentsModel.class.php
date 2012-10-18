<?php
class user_commentsModel extends RelationModel{
	protected $_link = array(
	   'user'=>array(
	        'mapping_type'  => BELONGS_TO,
            'class_name'    => 'user',
            'foreign_key'   => 'uid',
	   ),
	);
}