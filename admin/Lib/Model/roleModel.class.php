<?php
class roleModel extends RelationModel{
	protected $_link=array(
       'admin'=>array(
           'mapping_type'  => HAS_MANY,
           'class_name'    => 'admin',
           'foreign_key'   => 'id',
	),
	);
}