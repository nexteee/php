<?php
class items_collectAction extends baseAction
{
    public function index()
    {
        $items_site_mod = D('items_site');
        import("ORG.Util.Page");
        $count = $items_site_mod->count();
        $p = new Page($count,20);
        $sites_list = $items_site_mod->limit($p->firstRow.','.$p->listRows)->select();
        $key = 1;
        foreach($sites_list as $k=>$val){
            $sites_list[$k]['key'] = ++$p->firstRow;
        }
        $page = $p->show();
        $this->assign('page', $page);
        $this->assign('sites_list', $sites_list);
        $big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=items_collect&a=add\', title:\'添加来源\', width:\'500\', height:\'250\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加来源');
        $this->assign('big_menu',$big_menu);
        $this->display();
    }
    public function add()
    {
        if (isset($_POST['dosubmit'])) {
            $data['name'] = isset($_POST['name']) && trim($_POST['name']) ? trim($_POST['name']) : $this->error('请填写来源名称');
            $data['alias'] = isset($_POST['alias']) && trim($_POST['alias']) ? trim($_POST['alias']) : $this->error('请填写唯一标识');
            $data['site_domain'] = isset($_POST['site_domain']) && trim($_POST['site_domain']) ? trim($_POST['site_domain']) : $this->error('请填写网站域名');
            $data['collect_url'] = isset($_POST['collect_url']) && trim($_POST['collect_url']) ? trim($_POST['collect_url']) : '';
            $data['type'] = isset($_POST['type']) && intval($_POST['type']) ? 1 : 0;
            if ($_FILES['site_logo']['name']!='') {
                $upload_list = $this->_upload($_FILES['site_logo']);
                $data['site_logo'] = $upload_list['0']['savename'];
            } else {
                $this->error('请上传网站LOGO');
            }

            $items_site_mod = D('items_site');
            $result = $items_site_mod->add($data);
            if($result){
                $this->success(L('operation_success'), '', '', 'add');
            }else{
                $this->error(L('operation_failure'));
            }
        }
        $this->display();
    }

    public function edit()
    {
        $items_site_mod = D('items_site');
        if (isset($_POST['dosubmit'])) {
            $id = isset($_POST['id']) && intval($_POST['id']) ? intval($_POST['id']) : $this->error('参数错误');
            $data['name'] = isset($_POST['name']) && trim($_POST['name']) ? trim($_POST['name']) : $this->error('请填写来源名称');
            //$data['alias'] = isset($_POST['alias']) && trim($_POST['alias']) ? trim($_POST['alias']) : $this->error('请填写唯一标识');
            $data['site_domain'] = isset($_POST['site_domain']) && trim($_POST['site_domain']) ? trim($_POST['site_domain']) : $this->error('请填写网站域名');
            $data['collect_url'] = isset($_POST['collect_url']) && trim($_POST['collect_url']) ? trim($_POST['collect_url']) : '';
            $data['type'] = isset($_POST['type']) && intval($_POST['type']) ? 1 : 0;
            if ($_FILES['site_logo']['name']!='') {
                $upload_list = $this->_upload($_FILES['site_logo']);
                $data['site_logo'] = $upload_list['0']['savename'];
            }
            $result = $items_site_mod->where('id='.$id)->save($data);
            if(false !== $result){
                $this->success(L('operation_success'), '', '', 'edit');
            }else{
                $this->error(L('operation_failure'));
            }
        }
        $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('参数错误');
        $site_info = $items_site_mod->where('id='.$id)->find();
        $this->assign('site_info', $site_info);
        $this->display();
    }

    public function delete()
    {
        if((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的商品！');
        }
        $items_site_mod = D('items_site');
        if( isset($_POST['id'])&&is_array($_POST['id']) ){
            $ids = implode(',',$_POST['id']);
            $items_site_mod->delete($ids);
        }else{
            $id = intval($_GET['id']);
            $items_site_mod->where('id='.$id)->delete();
        }
        $this->success(L('operation_success'));
    }

    private function _upload($file)
    {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 3292200;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        $upload->savePath = './data/author/';
        $upload->thumb = true;
        $upload->imageClassPath = 'ORG.Util.Image';
        $upload->thumbPrefix = '32_,120_';
        $upload->thumbMaxWidth = '32,120';
        $upload->thumbMaxHeight = '32,120';
        $upload->saveRule = uniqid;
        $upload->thumbRemoveOrigin = true;

        if (!$upload->uploadOne($file)) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
        }
        return $uploadList;
    }

    public function taobaoapi()
    {
        $setting_mod = M('setting');
        if (isset($_POST['dosubmit'])) {
            $taobao['usernick'] = isset($_POST['usernick']) && trim($_POST['usernick']) ? trim($_POST['usernick']) : $this->error('请填写帐号');
            $taobao['pid'] = isset($_POST['pid']) && trim($_POST['pid']) ? trim($_POST['pid']) : $this->error('请填写pid');
            $taobao['appkey'] = isset($_POST['appkey']) && trim($_POST['appkey']) ? trim($_POST['appkey']) : $this->error('请填写appkey');
            $taobao['appsecret'] = isset($_POST['appsecret']) && trim($_POST['appsecret']) ? trim($_POST['appsecret']) : $this->error('请填写appsecret');
            foreach( $taobao as $key=>$val ){
                $setting_mod->where("name='taobao_".$key."'")->save(array('data'=>$val));
            }
            $this->success('修改成功', U('items_collect/taobaoapi'));
        }
        $res = $setting_mod->where("name='taobao_usernick' OR name='taobao_pid' OR name='taobao_appkey' OR name='taobao_appsecret'")->select();
        foreach( $res as $val )
        {
            $taobaoset[$val['name']] = $val['data'];
        }
        $this->assign('taobao',$taobaoset);
        $this->display();
    }

    public function taobao_collect()
    {
        if (isset($_POST['dosubmit'])) {
            $cate_id = isset($_POST['cate_id']) && intval($_POST['cate_id']) ? intval($_POST['cate_id']) : $this->error('请选择分类');
            $keywords = isset($_POST['keywords']) && trim($_POST['keywords']) ? trim($_POST['keywords']) : $this->error('请填写关键词');
            $pages = isset($_POST['pages']) && intval($_POST['pages']) ? intval($_POST['pages']) : 1;
            $this->redirect('items_collect/taobao_collect_jump', array('cate_id'=>$cate_id,'keywords'=>$keywords,'pages'=>$pages));
        }
        //获取分类
        $cate_id = isset($_GET['cate_id']) && intval($_GET['cate_id']) ? intval($_GET['cate_id']) : $this->error('请选择分类');
        $cate_name = isset($_GET['cate_name']) && trim($_GET['cate_name']) ? trim($_GET['cate_name']) : '';
        $this->assign('cate_id', $cate_id);
        $this->assign('cate_name', $cate_name);
        $this->display();
    }
    public function taobao_batch_collect_jump(){
        $tags_cate_mod=D('items_tags_cate');
        $tags_mod=D('items_tags');
        $items_cate_mod = D('items_cate');
        $items_site_mod = D('items_site');
        $collect_taobao_mod = D('collect_taobao');

        $cate = isset($_REQUEST['cate'])?explode(',',$_REQUEST['cate']): $this->error('请选择分类');
        $index=isset($_REQUEST['cate_index'])?intval('cate_index'):0;
        //$pages =5;

        $cate_id=$cate[$index];
        $tags_cate=$tags_cate_mod->where('cate_id='.$cate_id)->select();
        //print_r($tags_cate_mod->getLastSql());
        //print_r($tags_cate);exit;
        /*
        foreach ($tags_cate as $tkey=>$tval){
            $keyword.=$tags_mod->where('id='.$tval['tag_id'])->getField('name')."  ";
        }
        */
        $keywords=$items_cate_mod->where('id='.$cate_id)->find();
        //print_r($keyword);exit;
        //$p = isset($_GET['p']) && intval($_GET['p']) ? intval($_GET['p']) : 1;//当前页

        $tb_top = $this->taobao_client();
        $req = $tb_top->load_api('TaobaokeItemsGetRequest');
        $req->setFields("num_iid,title,nick,pic_url,price,click_url,shop_click_url,seller_credit_score,item_location,volume");
        $req->setPid($this->setting['taobao_pid']);
        $req->setKeyword('男装');
        $req->setPageNo(1);
        $req->setPageSize(40);
        $resp = $tb_top->execute($req);
        $goods_list = (array)$resp->taobaoke_items;
        //print_r($goods_list);exit;

        $sid = $items_site_mod->where("alias='taobao'")->getField('id');

        $items_nums = 0;
        foreach ($goods_list['taobaoke_item'] as $item) {
            $item = (array)$item;
            $item['item_key'] = 'taobao_'.$item['num_iid'];
            $item['sid'] = $sid;
            $this->_collect_insert($item, $cate_id);
            $items_nums++;
        }
        //更新分类表商品数
        if ($items_nums>0) {
            $items_cate_mod->where('id='.$cate_id)->setInc('item_nums', $items_nums);
        }

        if ($key>=count($cate)) {
            //记录采集时间
            $islog = $collect_taobao_mod->where('cate_id='.$cate_id)->count();
            if ($islog) {
                $collect_taobao_mod->save(array('cate_id'=>$cate_id, 'collect_time'=>time()));
            } else {
                $collect_taobao_mod->add(array('cate_id'=>$cate_id, 'collect_time'=>time()));
            }
            $this->collect_success('采集完成', '', 'collect');
        } else {
            $this->collect_success('第 <em class="blue">'.$index.'</em> 个分类，开始采集下一页',
            U('items_collect/taobao_batch_collect_jump', array('cate'=>implode(',',$cate),'cate_index'=>$index+1)));
        }
    }
    public function taobao_collect_jump()
    {
        $cate_id= isset($_GET['cate_id']) && intval($_GET['cate_id']) ? intval($_GET['cate_id']) : $this->error('请选择分类');
        $keywords = isset($_GET['keywords']) && trim($_GET['keywords']) ? trim($_GET['keywords']) : $this->error('请填写关键词');
        $pages = isset($_GET['pages']) && intval($_GET['pages']) ? intval($_GET['pages']) : 1;

        $p = isset($_GET['p']) && intval($_GET['p']) ? intval($_GET['p']) : 1;//当前页
        $items_cate_mod = D('items_cate');
        $items_site_mod = D('items_site');
        $collect_taobao_mod = D('collect_taobao');
        $tb_top = $this->taobao_client();
        $req = $tb_top->load_api('TaobaokeItemsGetRequest');
        $req->setFields("num_iid,title,nick,pic_url,price,click_url,shop_click_url,seller_credit_score,item_location,volume");
        $req->setPid($this->setting['taobao_pid']);
        $req->setKeyword($keywords);
        $req->setPageNo($p);
        $req->setPageSize(40);
        $resp = $tb_top->execute($req);
        $goods_list = (array)$resp->taobaoke_items;
        //print_r($goods_list);exit;

        $sid = $items_site_mod->where("alias='taobao'")->getField('id');

        $items_nums = 0;
        foreach ($goods_list['taobaoke_item'] as $item) {
            $item = (array)$item;
            $item['item_key'] = 'taobao_'.$item['num_iid'];
            $item['sid'] = $sid;
            $this->_collect_insert($item, $cate_id);
            $items_nums++;
        }
        //更新分类表商品数
        if ($items_nums>0) {
            $items_cate_mod->where('id='.$cate_id)->setInc('item_nums', $items_nums);
        }

        if ($p>=$pages) {
            //记录采集时间
            $islog = $collect_taobao_mod->where('cate_id='.$cate_id)->count();
            if ($islog) {
                $collect_taobao_mod->save(array('cate_id'=>$cate_id, 'collect_time'=>time()));
            } else {
                $collect_taobao_mod->add(array('cate_id'=>$cate_id, 'collect_time'=>time()));
            }
            M('items_cate')->where(array('id'=>$cate_id))->save(array('id'=>$cate_id,'collect_time'=>time()));
            session('collect_cate_id',$cate_id);
            $this->collect_success('采集完成', '', 'collect');
        } else {
            $this->collect_success('第 <em class="blue">'.$p.'</em> 页采集完成，开始采集下一页，共 <em class="blue">'.$pages.'</em> 页', U('items_collect/taobao_collect_jump', array('cate_id'=>$cate_id,'keywords'=>$keywords,'pages'=>$pages,'p'=>$p+1)));
        }


    }

    public function collect_success($message, $jump_url, $dialog='')
    {
        $this->assign('message', $message);
        if(!empty($jump_url)) $this->assign('jump_url', $jump_url);
        if(!empty($dialog)) $this->assign('dialog', $dialog);
        $this->display(APP_PATH.'Tpl/'.C('DEFAULT_THEME').'/items_collect/collect_success.html');
        exit;
    }

    private function _collect_insert($item, $cate_id)
    {
        $items_mod = D('items');
        $items_tags_mod = D('items_tags');
        $items_tags_item_mod = D('items_tags_item');

        //需要判断商品是否已经存在
        $isset = $items_mod->where("item_key='".$item['item_key']."'")->getField('id');
        if ($isset) {
            return;
        }
        $add_time = time();
        if($this->setting['likes_status']=="0") {
            $item['volume'] = "0";
        }
        $item_id = $items_mod->add(array(
            'title' => strip_tags($item['title']),
            'cid' => $cate_id,
            'sid' => $item['sid'],
            'item_key' => $item['item_key'],
            'img' => $item['pic_url'].'_210x1000.jpg',
            'simg' => $item['pic_url'].'_64x64.jpg',
            'bimg' => $item['pic_url'],
            'price' => $item['price'],
            'url' => $item['click_url'],
            'likes' => $item['volume'],
            'haves' => $item['volume'],
            'add_time' => $add_time,
            'last_time' => $add_time,
        ));
        //处理标签
        $tags = $items_tags_mod->get_tags_by_title(strip_tags($item['title']));
        if ($tags) {
            $tags = array_unique($tags);
            foreach ($tags as $tag) {
                $isset_id = $items_tags_mod->where("name='".$tag."'")->getField('id');
                if ($isset_id) {
                    $items_tags_mod->where('id='.$isset_id)->setInc('item_nums');
                    $items_tags_item_mod->add(array(
                        'item_id' => $item_id,
                        'tag_id' => $isset_id
                    ));
                } else {
                    $tag_id = $items_tags_mod->add(array('name'=>$tag));
                    $items_tags_item_mod->add(array(
                        'item_id' => $item_id,
                        'tag_id' => $tag_id
                    ));
                }
            }
        }
    }

    public function collect()
    {
        if(isset($_REQUEST['dosubmit'])){
            $cate=implode(',',$_REQUEST['cate']);

            header("location:".U('items_collect/taobao_batch_collect_jump?act=batch&cate='.$cate));
            exit;
        }
        $code = isset($_REQUEST['code']) ? trim($_REQUEST['code']) : $this->error('参数错误');
        $items_cate_mod = D('items_cate');

        switch ($code) {
            case 'taobao':
                $items_cate_mod = D('items_cate');
                $lists = $items_cate_mod->field('id,name,collect_time,pid')->order('ordid DESC')->select();
                $items_cate_list = array();
                foreach( $lists as $val ){
                    if ($val['pid']==0) {
                        $items_cate_list['parent'][$val['id']] = $val;
                    } else {
                        $items_cate_list['sub'][$val['pid']][] = $val;
                    }
                }
                $this->assign('items_cate_list', $items_cate_list);

                //采集成功后保存的 采集的分类的id
                if( session('collect_cate_id') ){
                    //获取采集分类的父分类id  及下级分类列表
                    $pid = $items_cate_mod->field('id,pid')->where(array('id'=>session('collect_cate_id')))->find();
                    $this->assign('item_pid',$pid['pid']);
                    $three_cate_lists = $items_cate_mod->field('id,name,collect_time,item_nums,item_likes')->where(array('pid'=>$pid['pid']))->select();
                    $this->assign('three_cate_lists',$three_cate_lists);
                }
                break;
            case 'mogujie':
                //提示采集器
                break;
            case 'meilishuo':
                //提示采集器
                break;
        }
        $this->assign('code',$code);
        $this->display();
    }

        /*
    * 获取子分类  至获取单级的分类
    */
    public function get_child_cates() {
        $items_cate_mod = D('items_cate');
        $parent_id = $this->_get('parent_id', 'intval');
        $cate_list = $items_cate_mod->field('id,name,collect_time,item_nums,item_likes')->where(array (
            'pid' => $parent_id
        ))->order('ordid DESC')->select();
        $content = "";
        $i = 0;
        foreach ($cate_list as $val) {
            $i++;
            if( $val['collect_time']>0 ){
                $collect_time = date('Y-m-d H:i:s',$val['collect_time']);
            }else{
                $collect_time = '';
            }
            $content .=
             "<tr>
                <td align=\"center\">".$i."</td>
                <td align=\"center\" style=\"padding-left:10px;\">&nbsp;&nbsp;".$val['name']."</td>
                <td align=\"center\">".$val['item_nums']."</td>
                <td align=\"center\">".$val['item_likes']."</td>
                <td align=\"center\" class=\"red\">".$collect_time."</td>
                <td align=\"center\">
                    <a href=\"javascript:collect(".$val['id'].", '".$val['name']."');\" class=\"blue\">采集</a>
                </td>
              </tr>";
        }
        $data = array (
            'content' => $content,
        );
        echo json_encode($data);
    }
}
?>