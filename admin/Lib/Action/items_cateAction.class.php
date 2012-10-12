<?php

class items_cateAction extends baseAction
{
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('items_cate');
    }

    //分类列表
    function index()
    {
        $cate_list = $this->_mod->get_list();
        $this->assign('items_cate_list', $cate_list['sort_list']);

        $this->display();
    }

    //添加分类数据
    function add()
    {
        if(isset($_POST['dosubmit'])){
            $items_cate_mod = M('items_cate');
            if( false === $vo = $items_cate_mod->create() ){
                $this->error( $items_cate_mod->error() );
            }
            if($vo['name']==''){
                $this->error('分类名称不能为空');
            }
            $result = $items_cate_mod->where("name='".$vo['name']."' AND pid='".$vo['pid']."'")->count();
            if($result != 0){
                $this->error('该分类已经存在');
            }

            if ($_FILES['img']['name'] != '') {
                $upload_list=$this->_upload($_FILES['img']);
                $vo['img'] = $upload_list['0']['savename'];
            }
            //保存当前数据
            $items_cate_id = $items_cate_mod->add($vo);
            $this->success('添加成功',U('items_cate/add'),1);
        }
        $cate_list = $this->_mod->get_list();
        $this->assign('items_cate_list',$cate_list['sort_list']);
        $this->assign('show_header', false);
        $this->display('edit');
    }

    function delete()
    {
        if((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的分类！');
        }
        $items_cate_mod = M('items_cate');
        if (isset($_POST['id']) && is_array($_POST['id'])) {
            /*
            foreach($_POST['id'] as $val){
            @unlink(ROOT_PATH."/data/items_cate/".$items_cate_mod->where('id='.$val)->getField('img'));
            }
            */
            $items_cate_mod->delete(implode(',', $_POST['id']));
        } else {
            $items_cate_id = intval($_GET['id']);
            /*
            @unlink(ROOT_PATH."/data/items_cate/".$items_cate_mod->where('id='.$items_cate_id)->getField('img'));
            */
            $items_cate_mod->delete($items_cate_id);
        }

        $this->success(L('operation_success'));
    }

    function edit()
    {
        if(isset($_POST['dosubmit'])){
            $items_cate_mod = M('items_cate');

            $old_items_cate = $items_cate_mod->where('id='.$_POST['id'])->find();
            //名称不能重复
            if ($_POST['name'] != $old_items_cate['name']) {
                if ($this->_items_cate_exists($_POST['name'], $_POST['pid'], $_POST['id'])) {
                    $this->error('分类名称重复！');
                }
            }

            //获取此分类和他的所有下级分类id
            $vids = array();
            $children[] = $old_items_cate['id'];
            $vr = $items_cate_mod->where('pid='.$old_items_cate['id'])->select();
            foreach ($vr as $val) {
                $children[] = $val['id'];
            }
            if (in_array($_POST['pid'], $children)) {
                $this->error('所选择的上级分类不能是当前分类或者当前分类的下级分类！');
            }

            $vo = $items_cate_mod->create();
            if ($_FILES['img']['name'] != '') {
                $upload_list=$this->_upload($_FILES['img']);
                $vo['img'] = $upload_list['0']['savename'];
                //删去老图片
                /*
                @unlink(ROOT_PATH."/data/items_cate/".$old_items_cate['img']);
                */
            }

            if( !isset($_POST['is_hots']) ){
                $vo['is_hots'] = 0;
            }
            if( !isset($_POST['status']) ){
                $vo['status'] = 0;
            }
            $result = $items_cate_mod->save($vo);
            if(false !== $result){
                $this->success('修改成功',U('items_cate/index'),1);
            }else{
                $this->error('修改失败',U('items_cate/index'));
            }
        }
        $cate_list = $this->_mod->get_list();
        $this->assign('items_cate_list', $cate_list['sort_list']);
        $items_cate_mod = M('items_cate');
        if( isset($_GET['id']) ){
            $items_cate_id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error(L('please_select').L('article_name'));
        }
        $items_cate_info = $items_cate_mod->where('id='.$items_cate_id)->find();

        $this->assign('items_cate_info',$items_cate_info);
        $this->assign('show_header', false);
        $this->display();
    }

    private function _items_cate_exists($name, $pid, $id=0)
    {
        $result = M('items_cate')->where("name='".$name."' AND pid='".$pid."' AND id<>'".$id."'")->count();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    function sort_order()
    {
        $items_cate_mod = M('items_cate');
        if (isset($_POST['listorders'])) {
            foreach ($_POST['listorders'] as $id=>$sort_order) {
                $data['ordid'] = $sort_order;
                $items_cate_mod->where('id='.$id)->save($data);
            }
            $this->success(L('operation_success'));
        }else{
            $this->error(L('operation_failure'));
        }
    }

    private function _upload($imgage, $path = '', $isThumb = false) {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 3292200;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        $upload->saveRule = uniqid;
        if (empty($savePath)) {
            $upload->savePath =ROOT_PATH.'/data/items_cate/';
        } else {
            $upload->savePath = $path;
        }

        if (!$upload->uploadOne($imgage)) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
        }
        return $uploadList;
    }
}
?>