<?php
class user_historyModel extends RelationModel{
    protected $_link = array(
        'user' => array(
            'mapping_type'  => BELONGS_TO ,
            'class_name'    => 'user',
            'foreign_key'   => 'uid',
            'as_fields'     => 'name,img',
        ),

    );

}