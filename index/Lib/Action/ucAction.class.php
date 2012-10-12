<?php
class ucAction extends baseAction {

    private $_action = array('account_basic', 'account_sns', 'account_pwd', 'account_invitation', 'album_info');

    public function _initialize() {
        parent::_initialize();
        if (($_REQUEST['act'] == 'del' || $_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit' ||
                in_array(ACTION_NAME, $this->_action))
                && !$this->check_login()
        ) {
            if ($this->isAjax()) {
                $this->ajaxReturn("not_login");
            } else {
                header('Location:' . U('uc/login'));
            }
        }
    }

    function uc_login_check() {
        if (is_null($this->uid)) {
            if ($this->isAjax()) {
                $this->ajaxReturn("not_login");
            } else {
                header('Location:' . U('uc/login'));
            }
        }
    }

    function index() {
        $this->uc_login_check();
        $items_mod = D("items");
        $album_mod = D('album');
        $album_items_mod = D('album_items');
        $items_likes_mod = D('items_likes');
        $items_comments_mod = D("items_comments");

        import("ORG.Util.Page");
        $where = "uid=" . $this->uid;

        $this->assign('album_num', $album_mod->where($where)->count());
        $res = $album_mod->where($where)->limit("0,4")->order("id desc")->select();

        foreach ($res as $key => $val) {
            $res2 = $album_items_mod->where("pid=" . $val['id'])->order("id desc")->limit("0,6")->select();
            $items = array();
            $like_num = 0;
            $comment_num = 0;
            foreach ($res2 as $key2 => $val2) {
                $img = $items_mod->field("likes,img")->where("id=" . $val2['items_id'])->find();
                $items[] = $img['img'];
                $like_num = $like_num + $img['likes'];

                $comment_num = $comment_num + $this->user_comments_mod->where('pid=' . $val2['items_id'] . ' and type="item,index"')->count();
            }
            $total_num = intval(6 - count($items));
            for ($num = 0; $num < $total_num; $num++) {
                $items[] = $this->site_root . "data/none_pic_v3.png";
            }
            $res[$key]['items'] = $items;
            $res[$key]['like_num'] = $like_num;
            $res[$key]['comment_num'] = $comment_num;
        }
        $this->assign('album_list', $res);

        $items_list = $items_mod->relation(true)->where('uid=' . $this->uid)->order('id DESC')->limit('20')->select();

        foreach ($items_list as $key => $val) {
            $items_list[$key]['comments_list'] = $this->items_comments_mod
                            ->relation('user')
                            ->where('items_id=' . $val['id'] . ' and status=1')
                            ->order('id desc')->limit('2')->select();
            if ($val['status'] == 0) {
                unset($items_list[$key]);
            }
        }
        $this->assign('items_list', $items_list);
        $this->display();
    }

    function me() {
        $this->uc_login_check();
        $user_history_mod = D('user_history');
        $user_mod = D('user');

        $where = 'uid=' . $this->uid;
        $count = $user_history_mod->where($where)->count();

        $pager = $this->pager($count);

        $res = $user_history_mod->relation(true)->where($where)
                ->limit($pager->firstRow . "," . $pager->listRows)
                ->order("id desc")
                ->select();

        $this->assign('history_list', $res);
        $this->display();
    }

    function album() {
        $this->uc_login_check();
        $user_mod = D('user');
        $items_mod = D("items");
        $album_mod = D('album');
        $album_items_mod = D('album_items');
        $user_follow_mod = D('user_follow');
        
        $type = empty($_REQUEST['type']) ? 'index' : $_REQUEST['type'];

        $this->assign('type', $type);
        import("ORG.Util.Page");

        if ($type == 'follow') {
            $res = $user_follow_mod->where("uid=" . $this->uid)->select();
            foreach ($res as $key => $val) {
                $ids[] = $val['fans_id'];
            }
            $where = "status='1' and uid in(" . implode(',', $ids) . ")";
        } else {
            $where = "status='1' and uid=" . $this->uid;
        }

        $count = $album_mod->where($where)->count();
        $p = new Page($count, 10);
        $res = $album_mod->where($where)->limit($p->firstRow . ',' . $p->listRows)->order("id desc")->select();

        foreach ($res as $key => $val) {
            $res2 = $album_items_mod->where("pid=" . $val['id'])->order("id desc")->limit("8")->select();
            $items = array();
            $like_num = 0;
            $comment_num = 0;
            foreach ($res2 as $key2 => $val2) {
                $img = $items_mod->field("likes,simg,img")->where("id=" . $val2['items_id'])->find();
                $items[] = $img['img'];
                $like_num = $like_num + $img['likes'];

                $comment_num = $comment_num + $this->user_comments_mod->where('pid=' . $val2['items_id'] . ' and type="item,index"')->count();
            }
            $total_num = intval(8 - count($items));
            for ($num = 0; $num < $total_num; $num++) {
                $items[] = $this->site_root . "data/none_pic_v3.png";
            }
            $res[$key]['items'] = $items;
            $res[$key]['like_num'] = $like_num;
            $res[$key]['comment_num'] = $comment_num;
            $res[$key]['user'] = $user_mod->where('id=' . $val['uid'])->find();
        }
        $this->assign('album_list', $res);
        $this->assign('page', $p->show_1());
        $this->display();
    }

    function album_info() {
        $this->uc_login_check();
        $act = empty($_REQUEST['act']) ? 'add' : $_REQUEST['act'];
        $id = $_REQUEST['id'];

        $album_mod = D('album');
        $album_items_mod = D('album_items');

        if ($act == 'del') {
            $album_items_mod->where('pid=' . $id)->delete();
            $album_mod->where('id=' . $id)->delete();

            $this->update_user_assoc_num('album');
            header("location:" . u('uc/album'));
        } else if ($act == 'edit') {
            $res = $album_mod->where('id=' . $id . ' and uid=' . $_SESSION['user_id'])->find();
            $this->assign('album', $res);
        }
        if (!empty($_POST['dosubmit'])) {
            $data = $album_mod->create();
            if ($act == 'add') {
                $data['add_time'] = time();
                $data['uid'] = $_SESSION['user_id'];
                $data['id'] = $album_mod->add($data);
            } else if ($act == 'edit') {
                $album_mod->save($data);
            }
            $this->update_user_assoc_num('album');
            header("location:" . u('album/details', array('id' => $data['id'], 'uid' => $_SESSION['user_id'])));
        }
        $album_cate_mod = D('album_cate');
        $this->assign('cate', $album_cate_mod->where('status=1')->select());
        $this->assign('act', $act);
        $this->display();
    }

    function album_items() {
        $this->uc_login_check();
        $album_items_mod = D('album_items');
        $album_mod = D('album');
        $user_mod = D('user');
        if (empty($_REQUEST['id'])) {
            header('location:' . $this->site_root);
        }

        $id = intval($_REQUEST['id']);
        $count = $album_items_mod->where('pid=' . $id)->count();
        $res = $album_items_mod->where('pid=' . $id)->select();
        $ids = array();
        foreach ($res as $val) {
            $ids[] = $val['items_id'];
        }
        $where = 'id in(' . implode(",", $ids) . ')';

        $res = $user_mod->where('id=' . $this->uid)->find();
        $info['album_who'] = $res["name"] . "的专辑";
        $res = $album_mod->where('id=' . $id)->find();
        $info['album_title'] = $res["title"];

        $this->assign('info', $info);
        $this->waterfall($count, $where);
    }

    function like() {
        $this->uc_login_check();
        $user_mod = D('user');
        $items_likes_mod = D('items_likes');

        $act = $_REQUEST['act'];
        $id = intval($_REQUEST['id']);

        if ($act == 'del') {
            $res = $items_likes_mod->where("items_id=$id and uid=".$_SESSION['user_id'])->delete();
            if (intval($res) > 0) {
                $count = $items_likes_mod->where('uid=' . $_SESSION['user_id'])->count();
                $data = array('like_num' => $count);
                $user_mod->where('uid=' . $_SESSION['user_id'])->save($data);
            }
            $this->ajaxReturn($res);
        } else if ($act == 'add') {
            $items_cate_mod = D('items_cate');
            $user_history_mod = D('user_history');

            $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : '';

            if (check_favorite('items_likes', $id)) {
                $this->ajaxReturn('yet_exist');
            }

            $like_num = 0;
            if ($id) {
                $items_mod = D('items');
                $last_time = time() - 3600 * 24;
                $like_days = $items_likes_mod->where('items_id=' . $id . " and add_time>" . $last_time)->count();

                $items_last = $items_mod->where('id=' . $id)->save(array('status' => '1', 'last_time' => time(), 'dlikes' => $like_days));
                $like_num = $items_mod->where('id=' . $id)->setInc('likes');
                $items = $items_mod->where('id=' . $id)->find();
                $items_cate_mod->where('id=' . $items['cid'])->setInc('item_likes');

                $data = array();
                $data['uid'] = $_SESSION['user_id'];
                $data['add_time'] = time();
                $data['info'] = "喜欢了一个宝贝~<br/><a href='" . u("item/index", array('id' => $id)) . "' target='_blank'><img src='" . $items['img'] . "'/></a>";
                $user_history_mod->add($data);

                $data = array();
                $count = $items_likes_mod->where('uid=' . $_SESSION['user_id'])->count();
                $data = array('like_num' => $count);
                $user_mod->where('id=' . $_SESSION['user_id'])->save($data);
            }
            $this->ajaxReturn($like_num);
        }
        $where = 'uid=' . $this->uid;
        $count = $items_likes_mod->where($where)->count();
        $res = $items_likes_mod->where($where)->select();
        $ids = array();
        foreach ($res as $val) {
            $ids[] = $val['items_id'];
        }
        $this->waterfall($count, 'id in(' . implode(",", $ids) . ')');
    }

    function share() {
        $this->uc_login_check();
        $items_mod = D('items');
        $user_mod = D('user');

        $act = $_REQUEST['act'];
        if ($act == 'del') {
            $res = $this->items_mod->del(intval($_REQUEST['id']));
            if (intval($res) > 0) {
                $count = $items_mod->where('uid=' . $_SESSION['user_id'])->count();
                $data = array('share_num' => $count);
                $user_mod->where('uid=' . $_SESSION['user_id'])->save($data);
            }
            $this->ajaxReturn($res);
        } else if ($act == 'add') {
            $items_cate_mod = D('items_cate');
            $items_site_mod = D('items_site');
            $items_tags_mod = D('items_tags');
            $items_tags_item_mod = D('items_tags_item');
            $user_history_mod = D('user_history');

            if (false === $data = $items_mod->create()) {
                $this->ajaxReturn('data_create_error');
            }
            $data['add_time'] = time();
            $author = isset($_POST['author']) ? $_POST['author'] : '';

            $data['sid'] = $items_site_mod->where("alias='" . $author . "'")->getField('id');
            $data['uid'] = $_SESSION['user_id'];
            if (($_POST['cid'] != "")&&($_POST['sid'] != "")&&($_POST['pid'] != "")) {
                $data['cid']    = $_POST['cid'];
                $data['level']  = "3";
            } elseif (($_POST['sid'] != "")&&($_POST['pid'] != "")) {
                $data['cid'] = $_POST['sid'];
                $data['level'] = "2";               
            }elseif ($_POST['pid'] != "") {
                $data['cid'] = $_POST['pid'];
                $data['level'] = "1";
            }elseif ($_POST['pid'] == "") {
                $this->error('请选择分类');
            }
            
            $new_item_id = $items_mod->add($data);
            $items_cate_mod->where('id=' . $data['cid'])->setInc('item_nums');

            $count = $items_mod->where('uid=' . $_SESSION['user_id'])->count();
            $data = array('share_num' => $count);
            $user_mod->where('uid=' . $_SESSION['user_id'])->save($data);

            //动态
            $res = $items_mod->where('id=' . $new_item_id)->find();
            $data = array();
            $data['uid'] = $_SESSION['user_id'];
            $data['add_time'] = time();
            $data['info'] = "分享了了一个宝贝~<br/>"
                    . "<a href='" . u("item/index", array('id' => $new_item_id)) . "' target='_blank'>"
                    . "<img src='" . $res['img'] . "'/></a>";

            $user_history_mod->add($data);

            if ($new_item_id) {
                $tags = $_POST['tags'];
                if ($tags) {
                    $tags_arr = explode(' ', $tags);
                    $tags_arr = array_unique($tags_arr);
                    foreach ($tags_arr as $tag) {
                        $isset_id = $items_tags_mod->where("name='" . $tag . "'")->getField('id');
                        if ($isset_id) {
                            $items_tags_mod->where('id=' . $isset_id)->setInc('item_nums');
                            $items_tags_item_mod->add(array(
                                'item_id' => $new_item_id,
                                'tag_id' => $isset_id
                            ));
                        } else {
                            $tag_id = $items_tags_mod->add(array('name' => $tag));
                            $items_tags_item_mod->add(array(
                                'item_id' => $new_item_id,
                                'tag_id' => $tag_id
                            ));
                        }
                    }
                }
                $items_cate_mod->setInc('item_nums', 1);
            }
            $this->ajaxReturn('success');
        }
        $where = 'uid=' . $this->uid;
        $count = $items_mod->where($where)->count();
        $order = $this->_get('order', 'trim', 'id');
        $this->assign('order', $order);
        $this->waterfall($count, $where, $order . ' DESC');
    }

    function account_basic() {
         $user_mod = D('user');
        if (isset($_POST['dosubmit'])) {
            $count = $user_mod->where('id!=' . $_SESSION['user_id'] . " and name='" . trim($_POST['name']) . "'")->count();
            if ($count) {
                $this->assign('err', array('err' => 0, 'msg' => '昵称已经存在!'));
                $this->display();
                exit;
            }
            $count = $user_mod->where('id!=' . $_SESSION['user_id'] . " and email='" . trim($_POST['email']) . "'")->count();
            if ($count > 0) {
                $this->assign('err', array('err' => 0, 'msg' => '邮箱已经被注册!'));
                $this->display();
                exit;
            }
            $data = $user_mod->create();
            
            if ($_FILES['img']['name'] != '') {
                $path = "data/user/".date("Y-m-d")."/";
                $upload_list = $this->_upload($_FILES['img'], $path);
                $data['img'] = __ROOT__."/".$path . "m_" . $upload_list['0']['savename'];
            }
            $user_mod->save($data);
            $this->assign('user', $user_mod->where('id=' . $_SESSION['user_id'])->find());
            $this->assign('err', array('err' => 1, 'msg' => '修改成功!'));
        }
        $this->display();
    }

    function account_sns() {
        $res = $this->user_openid_mod->where('uid=' . $_SESSION['user_id'])->select();
        foreach ($res as $key => $val) {
            $this->assign('bind_' . $val['type'], true);
        }
        $this->display();
    }

    function account_pwd() {
        if (isset($_POST['dosubmit'])) {
            $passwd = trim($this->user_mod->where('id=' . $_SESSION['user_id'])->getField('passwd'));

            if (trim($passwd) != md5(trim($_POST['passwd']))) {
                $this->assign('err', array('err' => 0, 'msg' => '当前密码错误!'));
            } else {
                $data = array('passwd' => md5(trim($_POST['new_pwd'])));
                $this->user_mod->where('id=' . $_SESSION['user_id'])->save($data);
                $this->assign('err', array('err' => 1, 'msg' => '修改成功!'));
            }
        }
        $this->display();
    }

    function account_invitation() {
        $this->assign('share', array(
            'uc_url' => 'http://' . u('uc/index', array('uid' => $_SESSION['user_id'])),
            'info' => '女人天生爱逛街，和我们一起来逛吧！~' . $this->site_root
        ));
        $this->display();
    }

    function get_share_dialog() {
        $this->assign('cate_list', $this->get_cate_list());
        $this->display();
    }

    function items_collect() {
        $itemcollect_mod = D('itemcollect');
        $items_cate_mod = D('items_cate');
        $items_tags_mod = D('items_tags');
        $items_mod = D('items');

        $url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
        //!$url && $this->ajaxReturn('', '', 0);
        $url = urldecode($url);

        $itemcollect_mod->url_parse($url);
        $data = $itemcollect_mod->fetch();
        //var_dump($data);exit;
        if (!is_array($data)) {
            $this->ajaxReturn(array('err' => 'remote_not_exist'));
        }
        $data['item']['item_key'] = $data['item']['key'];

        $tags = $items_tags_mod->get_tags_by_title($data['item']['title']);

        $data['item']['cid'] = $items_cate_mod->get_cid_by_tags($tags);
        $data['item']['tags'] = implode(' ', $tags);
        $item_id = $items_mod->where("item_key='" . $data['item']['item_key'] . "'")->getField('id');

        if ($item_id) {
            $this->ajaxReturn(array('err' => 'yet_exist', 'url' => __APP__ . "?m=item&a=index&id=" . $item_id));
        }
        $this->ajaxReturn($data['item']);
    }
    function items_del() {
        $items_mod = D('items');
        $items_likes_mod = D('items_likes');
        $items_pics_mod = D('items_pics');
        $album_items_mod = D('album_items');
        $user_comments_mod = D('user_comments');
        $items_tags_item_mod = D('items_tags_item');
        
        if((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的分享！');
        }        
        $id = intval($_GET['id']);
        
        $items_mod->delete($id);
        $items_likes_mod->where('items_id='.$id)->delete();
        $items_pics_mod->where('item_id='.$id)->delete();
        $album_items_mod->where('items_id='.$id)->delete();
        $user_comments_mod->where('pid='.$id)->delete();
        $items_tags_item_mod->where('item_id='.$id)->delete();
        
        header('Location:' . U('uc/share'));
        exit;
    }

    function qq_login() {
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'callback';
        $_SESSION['state'] = md5(uniqid(rand(), TRUE));
        $redirect_uri = $this->site_root . "index.php?m=uc&a=qq_" . $type;

        $login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id="
                . $this->setting['qq_app_key'] . "&redirect_uri=" . urlencode($redirect_uri)
                . "&state=" . $_SESSION['state'];

        header("Location:$login_url");
    }

    function qq_callback() {

        if ($_REQUEST['state'] == $_SESSION['state']) { //csrf
            $token_url = "https://graph.qq.com/oauth2.0/token";
            $aGetParam = array(
                "grant_type" => "authorization_code",
                "client_id" => $this->setting['qq_app_key'],
                "client_secret" => $this->setting['qq_app_Secret'],
                "code" => $_REQUEST["code"],
                "redirect_uri" => $this->site_root . "index.php?m=uc&a=qq_callback"
            );

            $res = $this->get($token_url, $aGetParam);

            if (trim($res) == '') {
                header( 'Content-Type: text/html; charset=UTF-8');
                 $this->error('无法获取认证！');
                 eixt;
            }
            if (strpos($res, "callback") !== false) {
                $lpos = strpos($res, "(");
                $rpos = strrpos($res, ")");
                $res = substr($res, $lpos + 1, $rpos - $lpos - 1);
                $msg = json_decode($res);
                if (isset($msg->error)) {
                    echo "<h3>error:</h3>" . $msg->error;
                    echo "<h3>msg  :</h3>" . $msg->error_description;
                    exit;
                }
            }
            parse_str($res, $res);
            $_SESSION["access_token"] = $res['access_token'];
        }
        $url = "https://graph.qq.com/oauth2.0/me";

        $str = $this->get($url, array('access_token' => $_SESSION['access_token']));

        if (strpos($str, "callback") !== false) {
            $lpos = strpos($str, "(");
            $rpos = strrpos($str, ")");
            $str = substr($str, $lpos + 1, $rpos - $lpos - 1);
        }
        $res = json_decode($str);

        $_SESSION['openid'] = $res->openid;

        $user_openid = $this->user_openid_mod->where("openid='" . $res->openid . "'")->find();
        $is_new = false;

        if ($user_openid) {
            if ($this->user_mod->where('id=' . $user_openid['uid'])->count() > 0) {
                $_SESSION['user_id'] = $user_openid['uid'];
                $data = array('last_time' => time(), 'last_ip' => $_SERVER['REMOTE_ADDR']);
                $this->user_mod->where('id=' . $_SESSION['user_id'])->save($data);
            } else {
                $this->user_openid_mod->where("openid='" . $user_openid . "'")->delete();
                $is_new = true;
            }
        } else {
            $is_new = true;
        }
        if ($is_new) {
            $url = "https://graph.qq.com/user/get_user_info?"
                    . "access_token=" . $_SESSION['access_token']
                    . "&openid=" . $_SESSION['openid']
                    . "&oauth_consumer_key=" . $this->setting['qq_app_key']
                    . "&format=json";
            $url = "https://graph.qq.com/user/get_user_info";
            $param = array(
                'access_token' => $_SESSION['access_token'],
                "openid" => $_SESSION['openid'],
                "oauth_consumer_key" => $this->setting['qq_app_key'],
                "format" => 'json',
            );

            $res = $this->get($url, $param);

            if ($res == false) {
                 $this->error('获取用户信息失败！');
                 exit;
            }
            $res = json_decode($res);
            $qq_info = array('user_info' => $res);
            $data = array(
                'name' => $res->nickname,
                'img' => $res->figureurl_2,
                'last_time' => time(),
                'last_ip' => $_SERVER['REMOTE_ADDR'],
                'add_time' => time(),
                'ip' => $_SERVER['REMOTE_ADDR'],
            );

            $_SESSION['user_id'] = $this->user_mod->add($data);
            $data = array(
                'type' => 'qq',
                'uid' => $_SESSION['user_id'],
                'openid' => $_SESSION['openid'],
                'info' => serialize($qq_info),
            );
            $this->user_openid_mod->add($data);
            header('Location:' . U('uc/sign'));
            exit;
        }
        $_SESSION['login_type'] = 'qq';

        header('Location:' . urldecode($_COOKIE['redirect']));
    }

    function qq_bind() {

        if ($_REQUEST['state'] == $_SESSION['state']) {

            $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
                    . "client_id=" . $this->setting['qq_app_key']
                    . "&redirect_uri=" . urlencode($this->site_root . "index.php?m=uc&a=qq_callback")
                    . "&client_secret=" . $this->setting['qq_app_Secret']
                    . "&code=" . $_REQUEST["code"];

            $res = array();
            parse_str(file_get_contents($token_url), $res);
        }
        $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" . $res['access_token'];
        $str = file_get_contents($graph_url);
        if (strpos($str, "callback") !== false) {
            $lpos = strpos($str, "(");
            $rpos = strrpos($str, ")");
            $str = substr($str, $lpos + 1, $rpos - $lpos - 1);
        }
        $res = json_decode($str);

        $user_openid = $this->user_openid_mod->where("openid='" . $res->openid . "' and uid=" . $_SESSION['user_id'])->find();

        if ($user_openid) {
            $this->error('已经绑定！');
            exit;
        } else {
            $data = array(
                'type' => 'qq',
                'uid' => $_SESSION['user_id'],
                'openid' => $res->openid,
                'info' => serialize($qq_info),
            );
            $this->user_openid_mod->add($data);
        }
        header('Location:' . U('uc/account_sns'));
        exit;
    }

    function sns_unbind() {
        $type = $_REQUEST['type'];
        if (!isset($type))
            exit;
        $this->user_openid_mod->where('uid=' . $_SESSION['user_id'] . " and type='$type'")->delete();
        header('Location:' . U('uc/account_sns'));
        exit;
    }

    function sina_login() {
        require_once ROOT_PATH . '/includes/saetv2.ex.class.php';
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'callback';
        $redirect_uri = $this->site_root . "index.php?m=uc&a=sina_" . $type;

        $o = new SaeTOAuthV2($this->setting['sina_app_key'], $this->setting['sina_app_Secret']);
        $login_url = $o->getAuthorizeURL($redirect_uri);

        header("Location:$login_url");
    }

    function sina_callback() {
        require_once ROOT_PATH . '/includes/saetv2.ex.class.php';

        $o = new SaeTOAuthV2($this->setting['sina_app_key'], $this->setting['sina_app_Secret']);

        if (isset($_REQUEST['code'])) {
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = $this->site_root . "index.php?m=uc&a=sina_callback";

            try {
                $token = $o->getAccessToken('code', $keys);
            } catch (OAuthException $e) {
                
            }
        }

        $c = new SaeTClientV2($this->setting['sina_app_key'],
            $this->setting['sina_app_Secret'],
            $token['access_token'], '');
        if (!$token) {
            $this->error('登录失败！');
            exit;
        }
       
        $_SESSION['token'] = $token;
        $_SESSION['access_token'] = $token['access_token'];
        $_SESSION['openid'] = $token['uid'];
        $user_openid = $this->user_openid_mod->where("openid='" . $token['uid'] . "'")->find();

        $is_new = false;
        if ($user_openid) {
            if ($this->user_mod->where('id=' . $user_openid['uid'])->count() > 0) {
                $_SESSION['user_id'] = $user_openid['uid'];
                $data = array('last_time' => time(), 'last_ip' => $_SERVER['REMOTE_ADDR']);
                $this->user_mod->where('id=' . $_SESSION['user_id'])->save($data);
            } else {
                $this->user_openid_mod->where("openid='" . $token['uid'] . "'")->delete();
                $is_new = true;
            }
        } else {
            $is_new = true;
        }
       
        if ($is_new) {
            $res = $c->show_user_by_id($token['uid']);
            if ($res['error_code'] == 21321) {
                $this->error('新浪微博网站接入审核未通过，无法获取该账户资料！');
                exit;
            }
            $sina_info = array('user_info' => $res);
            $data = array(
                'name' => $res['screen_name'],
                'img' => $res['profile_image_url'],
                'last_time' => time(),
                'last_ip' => $_SERVER['REMOTE_ADDR'],
                'add_time' => time(),
                'ip' => $_SERVER['REMOTE_ADDR'],
            );
            $_SESSION['user_id'] = $this->user_mod->add($data);
            $data = array(
                'type' => 'sina',
                'uid' => $_SESSION['user_id'],
                'openid' => $_SESSION['openid'],
                'info' => serialize($sina_info),
            );
            $this->user_openid_mod->add($data);
            header('Location:' . U('uc/sign'));
            exit;
        }
        $_SESSION['login_type'] = 'sina';
        header('Location:' . urldecode($_COOKIE['redirect']));
    }

    function sina_bind() {
        require_once ROOT_PATH . '/includes/saetv2.ex.class.php';

        $o = new SaeTOAuthV2($this->setting['sina_app_key'], $this->setting['sina_app_Secret']);

        if (isset($_REQUEST['code'])) {
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = $this->site_root . "index.php?m=uc&a=sina_callback";

            try {
                $token = $o->getAccessToken('code', $keys);
            } catch (OAuthException $e) {
                
            }
        }

        $c = new SaeTClientV2($this->setting['sina_app_key'],
                        $this->setting['sina_app_Secret'],
                        $token['access_token'], '');
        if (!$token) {
            $this->error('登录失败！');
            exit;             
        }

        $user_openid = $this->user_openid_mod->where("openid='" . $token['uid'] . "' and uid=" . $_SESSION['user_id'])->find();

        if ($user_openid) {
            $this->error('已经绑定！');
            exit;
        } else {
            $res = $c->show_user_by_id($token['uid']);
            if ($res) {
                $sina_info = array('user_info' => $res);
                $data = array(
                    'type' => 'sina',
                    'uid' => $_SESSION['user_id'],
                    'openid' => $token['uid'],
                    'info' => serialize($sina_info),
                );
                $this->user_openid_mod->add($data);
            } else {
                $this->error('获取用户信息失败！');
                exit;
            }
        }
        header('Location:' . U('uc/account_sns'));
        exit;
    }

    function login() {
        if ($this->isAjax()) {
            $user = $this->user_mod->where("(name='" . trim($_POST['name']) . "' or email='" . trim($_POST['name']) . "') and passwd='" . md5(trim($_POST['passwd'])) . "' and status='1'")->find();

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $res = $this->user_mod->where("id='" . $_SESSION['user_id'] . "'")->setField(array('last_ip' => $_SERVER['REMOTE_ADDR'], 'last_time' => time()));
                $this->ajaxReturn(array('err' => 1, 'msg' => '登录成功!'));
            } else {
                $this->ajaxReturn(array('err' => 0, 'msg' => '<br><font color=red>帐号或密码错误!</font>'));
            }
        }

        if ($this->check_login())
            header('Location:' . U('index/index'));

        if (isset($_POST['dosubmit'])) {
            $user = $this->user_mod->where("(name='" . trim($_POST['name']) . "' or email='" . trim($_POST['name']) . "') and passwd='" . md5(trim($_POST['passwd'])) . "' and status='1'")->find();
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $res = $this->user_mod->where("id='" . $_SESSION['user_id'] . "'")->setField(array('last_ip' => $_SERVER['REMOTE_ADDR'], 'last_time' => time()));
                header('Location:' . urldecode($_COOKIE['redirect']));
            } else {
                $this->assign('err', array('err' => 0, 'msg' => '帐号或密码错误!'));
            }
        }
        $this->display();
    }

    function register() {
        if ($this->check_login()) {
            header('location:' . u('index/index'));
        }
        if (isset($_POST['dosubmit'])) {
            $data = $this->user_mod->create();
            $this->assign('data', $data);
            $flag = true;
            if ($this->user_mod->where("name='" . trim($data['name']) . "'")->count()) {
                $this->assign('err', array('err' => 0, 'msg' => '昵称已存在!'));
                $flag = false;
            }
            if (strlen(trim($data['email'])) > 0) {
                if ($this->user_mod->where("email='" . trim($data['email']) . "'")->count()) {
                    $this->assign('err', array('err' => 0, 'msg' => '邮箱已经存在!'));
                    $flag = false;
                }
            }
            if ($_SESSION['verify'] != md5($_POST['verify'])) {
                $this->assign('err', array('err' => 0, 'msg' => '验证码错误!'));
                $flag = false;
            }
            if ($flag) {
                $data['ip'] = $_SERVER['REMOTE_ADDR'];
                $data['add_time'] = time();
                $data['passwd'] = md5(trim($data['passwd']));
                $id = $this->user_mod->add($data);
                $_SESSION['user_id'] = $id;
                header('location:' . u('uc/index'));
            }
        }
        $this->display();
    }

    function logout() {
        if ($_SESSION['login_type'] == 'sina') {
            $url = "https://api.weibo.com/2/account/end_session.json?access_token=" . $_SESSION['access_token'] . "&source" . $this->setting['sina_app_key'];
            $res = file_get_contents($url);
        }

        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        session_destroy();
        header('Location:./');
        //header('Location:'.urldecode($_COOKIE['redirect']));
    }

    function sign() {
        if (isset($_POST['dosubmit'])) {
            $count = $this->user_mod->where('id!=' . $_SESSION['user_id'] . " and name='" . trim($_POST['name']) . "'")->count();
            if ($count) {
                $this->assign('err', array('err' => 0, 'msg' => '昵称已经存在!'));
                $this->display();
                exit;
            }
            if (strlen(trim($_POST['passwd'])) < 6) {
                $this->assign('err', array('err' => 0, 'msg' => '密码至少为6位!'));
                $this->display();
                exit;
            }
            $data = array(
                'name' => trim($_POST['name']),
                'passwd' => md5(trim($_POST['passwd'])),
            );
            $this->user_mod->where('id=' . $_SESSION['user_id'])->save($data);
            header('Location:' . U('uc/index'));
        }
        $this->display();
    }

    function add_comment() {
        $uid = $_POST['uid'];
        if ($uid != $_SESSION['user_id']) {
            exit;
        }
        $data = $this->items_comments_mod->create();
        $data['add_time'] = time();
        $data['info'] = $this->remove_html($data['info']);

        $this->items_comments_mod->add($data);

        $items_mod = D('items');
        $items_mod->where('id=' . $data['items_id'])->setInc('comments');

        $this->ajaxReturn('提交成功!');
    }

    function follow() {
        $act = $_REQUEST['act'];
        $user_follow_mod = D('user_follow');
        $user_mod = D('user');
        $user_history_mod = D('user_history');

        if ($act == 'add') {
            $data = $user_follow_mod->create();
            if (intval($data['fans_id']) == $_SESSION['user_id'])
                exit;

            $data['uid'] = $_SESSION['user_id'];
            $data['add_time'] = time();
            $user_follow_mod->add($data);
            $fans_id = $data['fans_id'];


            $u = $user_mod->where('id=' . $fans_id)->find();
            //动态
            $data = array();
            $href = U('uc/index', array('uid' => $u['id']));
            $name = $u['name'];

            $data['uid'] = $_SESSION['user_id'];
            $data['add_time'] = time();
            $data['info'] = "关注了<a href='$href'>@$name</a>";

            $user_history_mod->add($data);
        }else if ($act == 'del') {
            $fans_id = intval($_REQUEST['fans_id']);
            $user_follow_mod->where("fans_id=" . intval($_REQUEST['fans_id']) . " and uid=" . $_SESSION['user_id'])->delete();
        }
        if ($act == 'add' || $act == 'del') {
            //更新自己的关注数
            $data = array();
            $data['follow_num'] = $user_follow_mod->where('uid=' . $_SESSION['user_id'])->count();
            $user_mod->where('id=' . $_SESSION['user_id'])->save($data);
            //print_r($user_mod->getLastSql());//exit;
            //更新被关注人的粉丝数
            $data = array();
            $data['fans_num'] = $user_follow_mod->where('fans_id=' . $fans_id)->count();
            $user_mod->where('id=' . $fans_id)->save($data);
            $this->ajaxReturn('success');
        }
        $where = "uid=" . $this->uid;
        $count = $user_follow_mod->where($where)->count();

        $pager = $this->pager($count);

        $res = $user_follow_mod->where($where)->limit($pager->firstRow . "," . $pager->listRows)->order("id desc")->select();
        foreach ($res as $key => $val) {
            //我是否关注了ta
            $res[$key]['is_follow'] = $this->user_follow_mod->where('fans_id=' . $res[$key]['fans_id'] . ' and uid=' . $_SESSION['user_id'])->count() > 0;
            //该用户最新动态
            $last_history = $this->get_last_history($res[$key]['fans_id']);
            $res[$key]['last_history'] = $last_history['info'];
            $res[$key]['last_time'] = $last_history['time'];
            $user = $user_mod->field("name,img,fans_num")->where("id=" . $res[$key]['fans_id'])->find();
            $res[$key] = array_merge($res[$key], $user);
        }
        $this->assign('list', $res);
        $this->assign('page', $pager);
        $this->display();
    }

    function fans() {
        $user_follow_mod = D('user_follow');
        $user_mod = D('user');

        $where = "fans_id=" . $this->uid;
        $count = $user_follow_mod->where($where)->count();

        $pager = $this->pager($count);

        $res = $user_follow_mod->where($where)->limit($pager->firstRow . "," . $pager->listRows)->order("id desc")->select();
        foreach ($res as $key => $val) {
            //我是否关注了ta
            $res[$key]['is_follow'] = $this->user_follow_mod->where('fans_id=' . $res[$key]['uid'] . ' and uid=' . $_SESSION['user_id'])->count() > 0;

            //该用户最新动态
            $last_history = $this->get_last_history($res[$key]['fans_id']);
            $res[$key]['last_history'] = $last_history['info'];
            $res[$key]['last_time'] = $last_history['time'];

            $user = $user_mod->field("name,img,fans_num")->where("id=" . $res[$key]['uid'])->find();
            $res[$key] = array_merge($res[$key], $user);
        }
        $this->assign('list', $res);
        $this->assign('page', $pager);
        $this->display();
    }

    function comments() {
        import("ORG.Util.Page");
        $items_mod = D('items');
        $user_comments_mod = D('user_comments');
        $user_history_mod = D('user_history');
        $act = $_REQUEST['act'];
        $type = $_REQUEST['type'];
        $pid = empty($_REQUEST['pid']) ? 0 : intval($_REQUEST['pid']);

        if ($act == 'add') {
            if (empty($_SESSION['user_id']))
                exit;

            //更新评论
            $data = $user_comments_mod->create();
            $data['add_time'] = time();
            $data['info'] = trim(strip_tags($_REQUEST['info']));
            if($data['info']=="") {
                $data['info'] = "非法字符，系统自动屏蔽！";
                $data['status'] = "0";
            }
            $data['uid'] = $_SESSION['user_id'];
            $user_comments_mod->add($data);

            if ($type == 'item') {
                $arr = array(
                    'comments' => $this->user_comments_mod->where("pid=" . $pid . " and type='item' and status='1'" )->count(),
                );
                $items_mod->where('id=' . $pid)->save($arr);
           }
            //更新动态
            $data = $user_history_mod->create();
            $data['add_time'] = time();
            $data['uid'] = $_SESSION['user_id'];
            $user_history_mod->add($data);
            return;
        }

        $where = "status='1' and  type='" . $_REQUEST['type'] . "' and pid=$pid";
        $count = $user_comments_mod->where($where)->count();
        $p = new Page($count, 8);
        $list = $user_comments_mod->relation('user')->where($where)->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign('comments', array('list' => $list, 'page' => $p->show_comments(), 'count' => $count));
        if ($this->isAjax()) {
            $this->ajaxReturn(array('list' => $this->fetch('comments_list'), 'count' => $count));
        }
    }

    private function _upload($imgage, $path = '', $isThumb = true) {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 3292200;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');

        if (empty($path)) {
            $upload->savePath = './data/items/' . date("Y-m-d") . "/";
        } else {
            $upload->savePath = $path;
        }

        if ($isThumb === true) {
            $upload->thumb = true;
            $upload->imageClassPath = 'ORG.Util.Image';
            $upload->thumbPrefix = 'b_,m_,s_';
            $upload->thumbMaxWidth = '450,210,64';
            //设置缩略图最大高度
            $upload->thumbMaxHeight = '4500,1000,64';
            $upload->saveRule = uniqid;
            $upload->thumbRemoveOrigin = false;
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

    function share_result_dialog() {
        //$cate_list = $this->items_cate_mod->get_list();
        //$this->assign('cate_list', $cate_list['sort_list']);
        //$this->display();
        $items_cate_mod = D('items_cate');
         //商品分类最上级分类
        $item_cate_first_list = $items_cate_mod->field('id,name')->where(array('pid' => 0))->order('ordid DESC')->select();
        $this->assign('item_cate_first_list', $item_cate_first_list);

        $this->assign('site_list', $site_list);
        $this->display();
    }

    function get_last_history($uid) {
        $user_history_mod = D('user_history');
        $res = $user_history_mod->where("uid=$uid")->order("id desc")->find();
        $data['info'] = $res['info'];
        $data['time'] = $res['add_time'];
        return $data;
    }

    /*
     * 分享宝贝
     */

    public function share_item() {
        $this->uc_login_check();
        $items_mod = D('items');
        $items_cate_mod = D('items_cate');
        $items_site_mod = D('items_site');
        $items_tags_mod = D('items_tags');
        $items_pics_mod = D('items_pics');
        $items_tags_item_mod = D('items_tags_item');
        $user_mod = D('user');
        $user_history_mod = D('user_history');
        if (isset($_POST['dosubmit'])) {
            if ($_POST['title'] == '') {
                $this->error('请填写商品标题');
            }
            if (false === $data = $items_mod->create()) {
                $this->error($items_mod->error());
            }
            $data['add_time'] = time();
            $data['last_time'] = time();
            $data['info'] = trim(strip_tags($_POST['info']));
            
            if (($_POST['cid'] != "")&&($_POST['sid'] != "")&&($_POST['pid'] != "")) {
                $data['cid']    = $_POST['cid'];
                $data['level']  = "3";
            } elseif (($_POST['sid'] != "")&&($_POST['pid'] != "")) {
                $data['cid'] = $_POST['sid'];
                $data['level'] = "2";               
            }elseif ($_POST['pid'] != "") {
                $data['cid'] = $_POST['pid'];
                $data['level'] = "1";
            }elseif ($_POST['pid'] == "") {
                $this->error('请选择分类');
            }
            
            if ($_FILES['img']['name'] != '') {
                $upload_list = $this->_upload($_FILES['img']);
                $data['simg'] = __ROOT__.'/data/items/' . date("Y-m-d") . '/s_' . $upload_list['0']['savename'];
                $data['img']  = __ROOT__.'/data/items/' . date("Y-m-d") . '/m_' . $upload_list['0']['savename'];
                $data['bimg'] = __ROOT__.'/data/items/' . date("Y-m-d") . '/b_' . $upload_list['0']['savename'];
                //$data['img'] = $data['simg'] = $data['bimg'] = $this->site_root . 'data/items/m_' . $upload_list['0']['savename'];
            } elseif ($_POST['img_url'] != '') {
                //取得远程图片并保存到本地
                $dir = './data/items/' . date("Y-m-d");
                if(!file_exists($dir)) {
                    mkdir($dir,0777);
                }
                
                $url  = trim($_POST['img_url']);
                $exts = pathinfo($url);  
                $exts = strtolower($exts["extension"]);
                if($exts=="") {
                   header('Location:' . U('uc/share_item'));
                   exit();
               }
                
                $curl = curl_init($url); 
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,1); 
                $images = curl_exec($curl); 
                curl_close($curl);
               
                $fname = time().".".$exts;                
                $fpath = $dir."/".$fname;
                $tp  = fopen($fpath, 'a'); 
                fwrite($tp, $images);
                fclose($tp);
                
                //缩小图片三种格式
                import("ORG.Util.Image");
                $data['simg'] = __ROOT__.'/data/items/' . date("Y-m-d") . '/s_' . $fname;
                $data['bimg'] = __ROOT__.'/data/items/' . date("Y-m-d") . '/b_' . $fname;
                $data['img'] = __ROOT__.'/data/items/' . date("Y-m-d") . '/m_' . $fname;
                $thumbname =  './data/items/' . date("Y-m-d") . '/s_' . $fname;
                $res = Image::thumb($fpath, $thumbname, $type=$exts, $maxWidth=64, $maxHeight=1000);                
                $thumbname =  './data/items/' . date("Y-m-d") . '/m_' . $fname;
                $res = Image::thumb($fpath, $thumbname, $type=$exts, $maxWidth=210, $maxHeight=3000);                
                $thumbname =  './data/items/' . date("Y-m-d") . '/b_' . $fname;
                $res = Image::thumb($fpath, $thumbname, $type=$exts, $maxWidth=1000, $maxHeight=5000);                
            }  else {
                $this->error('商品图片不能为空');
            }
            /**/
            //来源
            $author = 'handel';
            $data['sid'] = $items_site_mod->where("alias='" . $author . "'")->getField('id');
            $data['uid'] = $_SESSION['user_id'];
            $data['item_key'] = 'handel_' . time();
           
            $item_id = $items_mod->where("item_key='" . $data['item_key'] . "'")->getField('id');
            if ($item_id) {
                $items_mod->where('id=' . $item_id)->save($data);
                $this->success(L('operation_success'));
            } else {
                $new_item_id = $items_mod->add($data);
            }
            $count = $items_mod->where('uid=' . $_SESSION['user_id'])->count();
            $data = array('share_num' => $count);
            $user_mod->where('uid=' . $_SESSION['user_id'])->save($data);
            
            //动态
            $res = $items_mod->where('id=' . $new_item_id)->find();
            $data = array();
            $data['uid'] = $_SESSION['user_id'];
            $data['add_time'] = time();
            $data['info'] = "分享了了一个宝贝~<br/>"
                    . "<a href='" . u("item/index", array('id' => $new_item_id)) . "' target='_blank'>"
                    . "<img src='" . $res['img'] . "'/></a>";

            $user_history_mod->add($data);

            if ($new_item_id) {
                //相册上传
                if ($_FILES['pic']['name'][0] != '') {
                    $pic_list = array();
                    $_upload_list = $this->_upload($_FILES['pic']);

                    foreach ($_upload_list as $_img) {
                        $pic_list[] = array(
                            'item_id' => $new_item_id,
                            'add_time' => time(),
                            'url' => __ROOT__."/data/items/" . date("Y-m-d") . "/b_" . $_img['savename'],
                        );
                    }
                    $items_pics_mod->addAll($pic_list);
                }
                //处理标签
                $tags = isset($_POST['tags']) && trim($_POST['tags']) ? trim($_POST['tags']) : '';
                if ($tags) {
                    $tags_arr = explode(' ', $tags);
                    $tags_arr = array_unique($tags_arr);
                    foreach ($tags_arr as $tag) {
                        $isset_id = $items_tags_mod->where("name='" . $tag . "'")->getField('id');
                        if ($isset_id) {
                            $items_tags_mod->where('id=' . $isset_id)->setInc('item_nums');
                            $items_tags_item_mod->add(array(
                                'item_id' => $new_item_id,
                                'tag_id' => $isset_id
                            ));
                        } else {
                            $tag_id = $items_tags_mod->add(array(
                                'name' => $tag
                                    ));
                            $items_tags_item_mod->add(array(
                                'item_id' => $new_item_id,
                                'tag_id' => $tag_id
                            ));
                        }
                    }
                }
                $items_cate_mod->setInc('item_nums', 1);
                header('Location:' . U('uc/share'));
            } else {
                $this->error('分享失败');
            }
        }

        $site_list = $items_site_mod->field('id,name,alias')->select();

        //商品分类最上级分类
        $item_cate_first_list = $items_cate_mod->field('id,name')->where(array('pid' => 0))->order('ordid DESC')->select();
        $this->assign('item_cate_first_list', $item_cate_first_list);

        $this->assign('site_list', $site_list);
        $this->display();
    }

    //自动获取标签
    function get_tags() {
        $items_tags_mod = D('items_tags');
        $title = $this->_get('title', 'trim', '');
        $tags = $items_tags_mod->get_tags_by_title($title);
        $data['tags'] = implode(' ', $tags);
        $this->ajaxReturn($data['tags']);
    }

    /*
     * 获取子分类  至获取单级的分类
     */

    public function get_child_cates() {
        $items_cate_mod = D('items_cate');
        $parent_id = $this->_get('parent_id', 'intval');
        $cate_list = $items_cate_mod->field('id,name')->where(array(
                    'pid' => $parent_id
                ))->order('ordid DESC')->select();
        $content = "<option value=''>--请选择--</option>";
        foreach ($cate_list as $val) {
            $content .= "<option value='" . $val['id'] . "'>" . $val['name'] . "</option>";
        }
        $data = array(
            'content' => $content,
        );
        echo json_encode($data);
    }

    private function _uploadone($imgage, $path = '', $isThumb = true) {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 3292200;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');

        if (empty($path)) {
            $upload->savePath = './data/items/';
        } else {
            $upload->savePath = $path;
        }

        if ($isThumb === true) {
            $upload->thumb = true;
            $upload->imageClassPath = 'ORG.Util.Image';
            $upload->thumbPrefix = 'm_';
            $upload->thumbMaxWidth = '450';
            //设置缩略图最大高度
            $upload->thumbMaxHeight = '450';
            $upload->saveRule = uniqid;
            $upload->thumbRemoveOrigin = true;
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
