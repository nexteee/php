<?php

class baseAction extends Action {

    public $seo = array();
    public $setting = array();
    public $like_list_mod;
    public $user_mod;
    public $items_mod;
    public $items_cate_mod;
    public $items_comments_mod;
    public $user_openid_mod;
    public $setting_mod;
    public $site_root;
    public $user_follow_mod;
    public $user_comments_mod;
    protected $config;
    public $uid;
    public $user; //自己
    public $u; //别人
    public $mod;
    public function _initialize() {
        $site_status = M('setting')->where(array('name' => 'site_status'))->getField('data');
        if ($site_status == '0') {
            $closed_reason = M('setting')->where(array('name' => 'closed_reason'))->getField('data');
            header("Content-type: text/html; charset=utf-8");
            echo $closed_reason;
            exit();
            //exit('网站暂停使用。'.$closed_reason);
        }

        include ROOT_PATH . '/includes/lib_common.php';
        $this->mod=new Model();
        $this->setting_mod = D("setting");
        $this->like_list_mod = D('like_list');
        $this->user_mod = D('user');
        $this->items_mod = D('items');
        $this->items_cate_mod = D('items_cate');
        $this->items_comments_mod = D('items_comments');
        $this->user_openid_mod = D('user_openid');
        $this->user_follow_mod = D('user_follow');
        $this->user_comments_mod = D('user_comments');

        $this->config = array('debug' => 0);
        $this->site_root = "http://" . $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] == 80 ? '' : ':' . $_SERVER['SERVER_PORT']) . __ROOT__ . "/";
        define("SITE_ROOT", $this->site_root);

        //网站配置
        $setting_mod = M('setting');
        $setting = $setting_mod->select();
        foreach ($setting as $val) {
            $set[$val['name']] = $val['data'];
        }
        $this->setting = $set;

        $this->assign('site_domain', $this->setting['site_domain']);
        $this->assign('site_name', $this->setting['site_name']);
        $this->assign('site_icp', $this->setting['site_icp']);
        $this->assign('statistics_code', $this->setting['statistics_code']);
        $this->assign('default_kw', $this->setting['default_kw']);
        $this->assign('index_pins', $this->setting['index_pins']);
        $this->assign('index_album', $this->setting['index_album']);

        $this->seo['seo_title'] = $this->setting['site_title'];
        $this->seo['seo_keys'] = $this->setting['site_keyword'];
        $this->seo['seo_desc'] = $this->setting['site_description'];
        $this->assign('seo', $this->seo);

        //url模式
        C('URL_MODEL', $this->setting['url_model']);
        //SEO
        $this->seo['seo_title'] = $this->setting['site_title'];
        $this->seo['seo_keys'] = $this->setting['site_keyword'];
        $this->seo['seo_desc'] = $this->setting['site_description'];
        $this->assign('seo', $this->seo);

        //关注我们
        $follow_us = array(
            'weibo_url' => $this->setting['weibo_url'],
            'qqweibo_url' => $this->setting['qqweibo_url'],
            'renren_url' => $this->setting['renren_url'],
            '163_url' => $this->setting['163_url'],
            'qqzone_url' => $this->setting['qqzone_url'],
            'douban_url' => $this->setting['douban_url'],
        );
        $this->assign('follow_us', $follow_us);

        $nav_mod = M('nav');
        //头部导航
        $nav_list['main'] = $nav_mod->order('sort_order ASC')->where('is_show=1 AND type=1')->select();
        $this->assign('nav_list', $nav_list);
        //友情链接
        $flink_mod = M('flink');
        $flink_list = $flink_mod->where('status=1')->select();
        $this->assign('flink_list', $flink_list);

        $this->uid = empty($_REQUEST['uid']) ? $_SESSION['user_id'] : intval($_REQUEST['uid']);
        $this->assign('uid', $this->uid);

        if ($this->check_login()) {
            $this->user = $this->user_mod->where('id=' . $_SESSION['user_id'])->find();
            if (trim($this->user['img']) == "") {
                $this->user['img'] = $this->site_root . "data/user/avatar.gif";
            }
            $this->assign('user', $this->user);
        }
        //当前浏览页的用户信息
        $this->u = $this->user_mod->where('id=' . $this->uid)->find();
        if (trim($this->u['img']) == "") {
            $this->u['img'] = $this->site_root . "data/user/avatar.gif";
        }
        //我是否关注了ta
        $this->u['is_follow'] = $this->user_follow_mod
                        ->where('fans_id=' . $this->uid . ' and uid=' . $_SESSION['user_id'])
                        ->count() > 0;
        //print_r($this->u);exit;
        $this->assign('u', $this->u);
        if ($this->uid == $_SESSION['user_id']) {
            $this->assign('me', true);
        }

        $this->assign('cate_list', $this->get_cate_list());
        $this->assign('module_name', MODULE_NAME);
        $this->assign('def', $this->js_init());
        $this->assign('request', $_REQUEST);
    }

    function get_group_items($cid) {
        $items_mod = M('items');
        $items_result = $items_mod->field('id,title,img,simg')
                ->where("status=1 and cid=" . $cid)->limit("0,9")
                ->order("is_index DESC,likes DESC")
                ->select();
        //print_r($items_mod->getLastSql());
        return $items_result;
    }

    function get_group_items_bysource($sid) {
        $items_mod = M('items');
        $items_result = $items_mod->field('id,cid,simg')->where("sid=" . $sid)->limit("0,9")->order("is_index DESC,likes DESC")->select();
        return $items_result;
    }

    protected function error($message, $url_forward = '', $ms = 3, $ajax = false) {
        $this->jumpUrl = $url_forward;
        $this->waitSecond = $ms;
        parent::error($message, $ajax);
    }

    protected function success($message, $url_forward = '', $ms = 3, $dialog = false, $ajax = false, $returnjs = '') {
        $this->jumpUrl = $url_forward;
        $this->waitSecond = $ms;
        $this->assign('dialog', $dialog);
        $this->assign('returnjs', $returnjs);
        parent::success($message, $ajax);
    }

    protected function check_login() {
        if (isset($_SESSION['user_id']) && $this->user_mod->where('id=' . $_SESSION['user_id'])->count() > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    protected function get_cate_list() {
        $items_cate_mod = D('items_cate');
        $result = $items_cate_mod->order('ordid DESC')->select();
        $cate_list = array();
        foreach ($result as $val) {
            if ($val['pid'] == 0) {
                $cate_list['parent'][$val['id']] = $val;
            }
            else {
                $cate_list['sub'][$val['pid']][] = $val;
            }
        }
        return $cate_list;
    }

    function remove_html($string, $sublen) {
        $string = strip_tags($string);
        $string = preg_replace('/\n/is', '', $string);
        $string = preg_replace('/ |　/is', '', $string);
        $string = preg_replace('/&nbsp;/is', '', $string);
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);
        if (count($t_string[0]) - 0 > $sublen)
            $string = join('', array_slice($t_string[0], 0, $sublen));
        else
            $string = join('', array_slice($t_string[0], 0, $sublen));
        return $string;
    }

    function js_init() {
        $port = $_SERVER['SERVER_PORT'] == 80 ? '' : ':' . $_SERVER['SERVER_PORT'];

        return json_encode(array(
                    "app" => __APP__,
                    "root" => "http://" . $_SERVER['SERVER_NAME'] . $port . __ROOT__ . "/",
                    "user_id" => $_SESSION['user_id'],
                    "uid" => $this->uid,
                    "module" => MODULE_NAME,
                    "action" => ACTION_NAME,
                    "tmpl" => "http://" . $_SERVER['SERVER_NAME'] . $port . __TMPL__,
                    "waterfall_sp" => $this->setting['waterfall_sp'],
                ));
    }

    /*
     * GET请求
     */

    function get($sUrl, $aGetParam) {

        $oCurl = curl_init();
        if (stripos($sUrl, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $aGet = array();
        foreach ($aGetParam as $key => $val) {
            $aGet[] = $key . "=" . urlencode($val);
        }
        curl_setopt($oCurl, CURLOPT_URL, $sUrl . "?" . join("&", $aGet));
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($this->config["debug"]) === 1) {
            echo "<tr><td class='narrow-label'>请求地址:</td><td><pre>" . $sUrl . "</pre></td></tr>";
            echo "<tr><td class='narrow-label'>GET参数:</td><td><pre>" . var_export($aGetParam, true) . "</pre></td></tr>";
            echo "<tr><td class='narrow-label'>请求信息:</td><td><pre>" . var_export($aStatus, true) . "</pre></td></tr>";
            if (intval($aStatus["http_code"]) == 200) {
                echo "<tr><td class='narrow-label'>返回结果:</td><td><pre>" . $sContent . "</pre></td></tr>";
                if ((@$aResult = json_decode($sContent, true))) {
                    echo "<tr><td class='narrow-label'>结果集合解析:</td><td><pre>" . var_export($aResult, true) . "</pre></td></tr>";
                }
            }
        }
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        }
        else {
            echo "<tr><td class='narrow-label'>返回出错:</td><td><pre>" . $aStatus["http_code"] . ",请检查参数或者确实是腾讯服务器出错咯。</pre></td></tr>";
            return FALSE;
        }
    }

    /*
     * POST 请求
     */

    function post($sUrl, $aPOSTParam) {

        $oCurl = curl_init();
        if (stripos($sUrl, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $aPOST = array();
        foreach ($aPOSTParam as $key => $val) {
            $aPOST[] = $key . "=" . urlencode($val);
        }
        curl_setopt($oCurl, CURLOPT_URL, $sUrl);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, join("&", $aPOST));
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);

        if (intval($this->config["debug"]) === 1) {
            echo "<tr><td class='narrow-label'>请求地址:</td><td><pre>" . $sUrl . "</pre></td></tr>";
            echo "<tr><td class='narrow-label'>POST参数:</td><td><pre>" . var_export($aPOSTParam, true) . "</pre></td></tr>";
            echo "<tr><td class='narrow-label'>请求信息:</td><td><pre>" . var_export($aStatus, true) . "</pre></td></tr>";
            if (intval($aStatus["http_code"]) == 200) {
                echo "<tr><td class='narrow-label'>返回结果:</td><td><pre>" . $sContent . "</pre></td></tr>";
                if ((@$aResult = json_decode($sContent, true))) {
                    echo "<tr><td class='narrow-label'>结果集合解析:</td><td><pre>" . var_export($aResult, true) . "</pre></td></tr>";
                }
            }
        }
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        }
        else {
            echo "<tr><td class='narrow-label'>返回出错:</td><td><pre>" . $aStatus["http_code"] . ",请检查参数或者确实是腾讯服务器出错咯。</pre></td></tr>";
            return FALSE;
        }
    }

    /*
     * 上传图片
     */

    function upload($sUrl, $aPOSTParam, $aFileParam) {
        //防止请求超时

        set_time_limit(0);
        $oCurl = curl_init();
        if (stripos($sUrl, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $aPOSTField = array();
        foreach ($aPOSTParam as $key => $val) {
            $aPOSTField[$key] = $val;
        }
        foreach ($aFileParam as $key => $val) {
            $aPOSTField[$key] = "@" . $val; //此处对应的是文件的绝对地址
        }
        curl_setopt($oCurl, CURLOPT_URL, $sUrl);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $aPOSTField);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($this->config["debug"]) === 1) {
            echo "<tr><td class='narrow-label'>请求地址:</td><td><pre>" . $sUrl . "</pre></td></tr>";
            echo "<tr><td class='narrow-label'>POST参数:</td><td><pre>" . var_export($aPOSTParam, true) . "</pre></td></tr>";
            echo "<tr><td class='narrow-label'>文件参数:</td><td><pre>" . var_export($aFileParam, true) . "</pre></td></tr>";
            echo "<tr><td class='narrow-label'>请求信息:</td><td><pre>" . var_export($aStatus, true) . "</pre></td></tr>";
            if (intval($aStatus["http_code"]) == 200) {
                echo "<tr><td class='narrow-label'>返回结果:</td><td><pre>" . $sContent . "</pre></td></tr>";
                if ((@$aResult = json_decode($sContent, true))) {
                    echo "<tr><td class='narrow-label'>结果集合解析:</td><td><pre>" . var_export($aResult, true) . "</pre></td></tr>";
                }
            }
        }
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        }
        else {
            echo "<tr><td class='narrow-label'>返回出错:</td><td><pre>" . $aStatus["http_code"] . ",请检查参数或者确实是腾讯服务器出错咯。</pre></td></tr>";
            return FALSE;
        }
    }

    function download($sUrl, $sFileName) {
        $oCurl = curl_init();

        set_time_limit(0);
        $oCurl = curl_init();
        if (stripos($sUrl, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($oCurl, CURLOPT_USERAGENT, $_SERVER["USER_AGENT"] ? $_SERVER["USER_AGENT"] : "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.7) Gecko/20100625 Firefox/3.6.7");
        curl_setopt($oCurl, CURLOPT_URL, $sUrl);
        curl_setopt($oCurl, CURLOPT_REFERER, $sUrl);
        curl_setopt($oCurl, CURLOPT_AUTOREFERER, true);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        file_put_contents($sFileName, $sContent);
        if (intval($this->config["debug"]) === 1) {
            echo "<tr><td class='narrow-label'>请求地址:</td><td><pre>" . $sUrl . "</pre></td></tr>";
            echo "<tr><td class='narrow-label'>请求信息:</td><td><pre>" . var_export($aStatus, true) . "</pre></td></tr>";
        }
        return(intval($aStatus["http_code"]) == 200);
    }

    function alert($str) {
        print_r("<script type='text/javascript'>alert(" . $str . ");</script>");
    }

    function pager($count, $pagesize = 20) {
        import("ORG.Util.Page");
        $pager = new Page($count, $pagesize);
        $this->assign('page', $pager->show_1());
        return $pager;
    }

    /*
     * 商品瀑布流
     * */

    function waterfall($count, $where, $order = "") {
        import("ORG.Util.Page");
        $items_mod = D("items");
        $user_comments_mod = D("user_comments");

        $p = !empty($_GET['p']) ? intval($_GET['p']) : 1;
        $sp = !empty($_GET['sp']) ? intval($_GET['sp']) : 1;
        $sp > $this->setting['waterfall_sp'] && exit;

        $list_rows = $this->setting['waterfall_sp'] * $this->setting['waterfall_items_num'];
        $s_list_rows = $this->setting['waterfall_items_num'];
        $show_sp = 0;

        $count > $s_list_rows && $show_sp = 1;
        $pager = new Page($count, $list_rows);

        $first_row = $pager->firstRow + $s_list_rows * ($sp - 1);
        $items_list = $items_mod->relation(true)->where($where)
                ->limit($first_row . ',' . $s_list_rows)->order($order)
                ->select();
        foreach ($items_list as $key => $val) {
            $sql = "SELECT c.*,u.img,u.name,u.add_time from " . C("DB_PREFIX") . "user_comments as c 
                    LEFT join " . C("DB_PREFIX") . "user as u on c.uid=u.id 
                    WHERE c.pid=$val[id] and c.type='item' and c.status='1'
                    ORDER BY c.add_time DESC LIMIT 0,3";
            $items_list[$key]['comments_list'] = $user_comments_mod->query($sql);
        }
        $this->assign('page', $pager->show_1());
        $this->assign('p', $p);
        $this->assign('show_sp', $show_sp);
        $this->assign('sp', $sp);
        $this->assign('items_list', $items_list);
        if ($this->isAjax() && $sp > 1) {
            header('Content-Type:text/html; charset=utf-8');
            echo($this->fetch('public:goods_list'));
        }
        else {
            $this->display();
        }
    }
    //转换get_cat_tree数组的id
    function get_cat_ids($cat){ 
        return $cat['id'].$this->get_child_ids($cat);
    }
    private function get_child_ids($cat){
        $ids="";
        foreach($cat['child'] as $key=>$val){
            $ids.=",".$val['id'].$this->get_child_ids($val);
        }
        return $ids;
    }
    function get_cat_tree($cid=0) {
        $res=$this->items_cate_mod->where("id=$cid and status=1")->find();
        $res['child']=$this->get_child_teee($res['id']);
        return $res;
    }
    private function get_child_teee($pid){
        $pid=intval($pid);
        if($pid==0)return false;
        $where="pid=$pid and status=1";
        if($this->items_cate_mod->where($where)->count()>0){
            $res=$this->items_cate_mod->where($where)->select();
            foreach($res as $key=>$val){
                $res[$key]['child']=$this->get_child_teee($val['id']);
            }
            return $res;
        }
    }
    /*
     * 获取专辑小图
     * */
    function get_album_list($where, $img_num = 9) {
        import("ORG.Util.Page");
        $album_mod = D("album");
        $album_items_mod = D('album_items');
        $items_mod = D("items");
        $items_comments_mod = D("items_comments");
        $items_mod = D("items");

        $p = !empty($_GET['p']) ? intval($_GET['p']) : 1;

        $count = $album_mod->where($where)->count();
        $pager = new Page($count, 20);

        $res = $album_mod->where($where)
                        ->limit($pager->firstRow . ',' . $pager->listRows)
                        ->order("sort_order desc")->select();
        foreach ($res as $key => $val) {
            $res[$key]['items_num'] = $album_items_mod->where("pid=" . $val['id'])->count();

            $res2 = $album_items_mod->where("pid=" . $val['id'])->order("id desc")->limit("0," . $img_num)->select();

            $items = array();
            $like_num = 0;
            $comment_num = 0;
            foreach ($res2 as $key2 => $val2) {
                $img = $items_mod->field("likes,simg")->where("id=" . $val2['items_id'])->find();
                $items[] = $img['simg'];
                $like_num = $like_num + $img['likes'];

                $comment_num = $comment_num + $items_comments_mod->where('items_id=' . $val2['items_id'])->count();
            }
            $total_num = intval(9 - count($items));
            for ($num = 0; $num < $total_num; $num++) {
                $items[] = $this->site_root . "data/none_pic_v3.png";
            }
            $res[$key]['items'] = $items;
            $res[$key]['like_num'] = $like_num;
            $res[$key]['comment_num'] = $comment_num;
        }
        $this->assign('page', $pager->show_1());
        $this->assign('p', $p);
        $this->assign('album_list', $res);

        $this->display();
    }

    function update_user_assoc_num($table, $field = null) {
        $mod = D($table);
        $field = is_null($field) ? $table : $field;

        $data = array(
            $field . '_num' => $mod->where('uid=' . $_SESSION['user_id'])->count(),
        );
        $this->user_mod->where('id=' . $_SESSION['user_id'])->save($data);
    }

}

?>