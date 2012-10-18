<?php
class albumAction extends baseAction{
    function index(){
        $album_cate_mod=D('album_cate');

        $cid = isset($_GET['cid']) && intval($_GET['cid']) ? intval($_GET['cid']) :0;

        $this->assign('album_cate',$album_cate_mod->where('status=1')->order("sort_order DESC,add_time DESC")->select());
        if(is_null($_REQUEST['cid'])){
            $where='recommend=1';
            $this->assign('cid',-1);
        }else{
            $where="cid=$cid";
            $this->assign('cid',$cid);
        }
        $where.=" and status=1";
        $this->get_album_list($where);
    }
    function details(){
        if(empty($_REQUEST['id'])||empty($_REQUEST['uid'])) {
            header('location:'.$this->site_root);
        }

        $album_items_mod=D('album_items');
        $album_mod=D('album');
        $user_mod=D('user');
        $id= intval($_REQUEST['id']);
        $count = $album_items_mod->where('pid='.$id)->count();
        $res=$album_items_mod->where('pid='.$id)->select();
        $ids=array();
        foreach($res as $val){
            $ids[]=$val['items_id'];
        }
        $where='id in('.implode(",",$ids).')';

        $user_res=$user_mod->where('id='.$this->uid)->find();
        $info['album_who']=$user_res["name"]."的专辑";

        $res=$album_mod->where('id='.$id)->find();
        $info['album_title']=$res["title"];
        $info['album_id']=$id;
        $info['remark']=$res['remark'];

        $info=array_merge($info,$user_res);
        $this->assign('info',$info);
        $this->waterfall($count,$where);
    }
    function album_items_add_dialog(){
        if(!$this->check_login()){
            $this->ajaxReturn("not_login");
        }
        $album_mod=D('album');
        $res=$album_mod->where('uid='.$_SESSION['user_id'])->select();
        $this->assign('list',$res);
        $this->display();
    }
    function items(){
        $act=$_REQUEST['act'];
        $album_items_mod=D('album_items');
        $album_mod=D('album');
        $user_history_mod=D('user_history');
        $items_mod=D('items');
        $user_comments_mod = D('user_comments');

        if($act=='add'){
            $data=$album_items_mod->create();
            $count=$album_items_mod->where("items_id=".$data['items_id']." and pid=".$data['pid'])->count();
            if($count>0){
                $this->ajaxReturn("yet_exist");
            }else{
                $data['add_time']=time();
                if(intval($data['pid'])==0){
                    $data['pid']=$album_mod->add(array(
                        'uid'=>$_SESSION['user_id'],
                        'add_time'=>time(),
                    ));
                }
                $album_items_mod->add($data);

                //将备注添加到评论中
                if(trim($data['remark'])!="") {
                    $comment = array();
                    $comment['uid']     = $_SESSION['user_id'];
                    $comment['pid']     = $data['items_id'];
                    $comment['info']    = $data['remark'];
                    $comment['add_time']= time();
                    $comment['type']    = 'item';
                    $comment['orig']    = 'album';
                    $comment['status']  = 1;
                    $user_comments_mod->add($comment);
                    $items_mod->where('id='.$data['items_id'])->setField(array('comments'=> 'comments+1','last_time' => time()));
                }
                
                $items_id = $data['items_id'];
                $res = $items_mod->where('id='.$items_id)->find();
                $data = array();
                $data['uid'] = $_SESSION['user_id'];
                $data['add_time'] = time();
                $data['info']="加入专辑~".$comment['info']."<br/><a href='".u("item/index",array('id'=>$items_id))."' target='_blank'><img src='".$res['img']."'/></a>";
                $user_history_mod->add($data);
                
                $this->ajaxReturn('success');
            }
        }else if($act=='del'){
            //是否是自己的专辑
            //$count=$album_mod->where('id='.intval($_REQUEST['pid']).' and uid='.$_SESSION['user_id'])->count();
            //if($count==0)return;
            $res = $album_items_mod->where('items_id='.intval($_REQUEST['id']))->delete();
            $this->ajaxReturn($res);
        }
    }
}