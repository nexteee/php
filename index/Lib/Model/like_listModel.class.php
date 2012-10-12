<?php
class like_listModel extends RelationModel{
    function del($id){
        $mod=D('like_list');
        return $mod->where("items_id=$id and uid=".$_SESSION['user_id'])->delete();     
    }
}