<?php
class userModel extends RelationModel{
    protected $_link = array(
        'items' => array(
            'mapping_type'  => HAS_MANY ,
            'class_name'    => 'items',
            'foreign_key'   => 'id',
        ),
        
        'user_comments' => array(
            'mapping_type'  => HAS_MANY ,
            'class_name'    => 'user_comments',
            'foreign_key'   => 'id',
        ),
    );
    public function get_user($id){
        $mod=D('user');
        return $mod->where('id='.$id)->find();
    }
}