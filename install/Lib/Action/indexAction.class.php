<?php

class indexAction extends Action {

    public function _initialize() {
        L(include LANG_PATH . 'common.php');
    }

    /**
     * 安装协议
     */
    public function index() {
        $this->assign('step_curr', 'eula');
        $eula_html = file_get_contents(LANG_PATH . 'eula.html');
        $this->assign('eula_html', $eula_html);
        if (isset($_POST['dosubmit'])) {
            if (!isset($_POST['accept']) || !$_POST['accept']) {
                $error_msg = L('please_accept');
                $this->assign('error_msg', $error_msg);
                $this->display();
            } else {
                unset($_POST);
                header('location:' . u('index/check'));
                //$this->check();
            }
        } else {
            $this->display();
        }
    }

    /**
     * 检测服务器环境
     */
    public function check() {
        $this->assign('step_curr', 'check');
        $flag = true;
        //检测文件夹权限
        $check_file = array(
            '/data',
            '/data/advert',
            '/data/author',
            '/data/items',
            '/data/logs',
            '/data/logs/taobao',
            '/data/news',
            '/admin/Runtime',
            '/index/Runtime',
            '/index/Conf/theme.php',
            '/config.inc.php',
        );
        $error = array();
        foreach ($check_file as $file) {
            $path_file = ROOT_PATH . $file;
            if (!file_exists($path_file)) {
                $error[] = $file . "不存在!";
                $flag = false;
                continue;
            }
            if (!is_writable($path_file)) {
                $error[] = $file . "不可读写!";
                $flag = false;
            }
        }
        if (!function_exists("curl_getinfo")) {
            $error[] = "系统不支持curl!";
            $flag = false;
        }

        if (!function_exists("gd_info")) {
            $error[] = "系统不支持GD!";
            $flag = false;
        }

        import("ORG.Io.Dir");
        $dir = new Dir;
        $dir->delDir("admin/Runtime");
        mkdir("admin/Runtime");
        $dir->delDir("index/Runtime");
        mkdir("index/Runtime");

        if (!$flag) {
            $this->assign('error', $error);
            $this->display('check');
        } else {
            $this->redirect('index/setconf');
        }
    }

    /**
     * 填写配置信息
     */
    public function setconf() {
        $this->assign('step_curr', 'setconf');
        $this->assign('database_name_tip', L('database_name_tip'));
        $this->assign('db_host', 'localhost');
        $this->assign('db_port', '3306');
        $this->assign('db_user', 'root');
        $this->assign('db_name', 'pinphp');
        $this->assign('db_prefix', 'pp_');

        $this->assign('admin_user', 'admin');
        $this->assign('db_pass', '');
        $this->assign('admin_pass', '');
        $this->assign('admin_pass_confirm', '');
        $this->assign('admin_email', '');

        if (isset($_POST['dosubmit'])) {
            foreach ($_POST as $key => $val) {
                $this->assign($key, $val);
            }
            extract($_POST);
            if (!$db_host || !$db_port || !$db_name || !$db_user || !$db_prefix || !$admin_user || !$admin_email || !$admin_pass) {
                $this->assign('error_msg', L('please_input_config_info'));
                $this->display();
                exit;
            }
            if (!$this->is_email($admin_email)) {
                $this->assign('error_msg', L('admin_email_format_incorrect'));
                $this->display();
                exit;
            }
            if ($admin_pass != $admin_pass_confirm) {
                $this->assign('error_msg', L('admin_pass_error'));
                $this->display();
                exit;
            }
            //试着连接数据库
            $conn = @mysql_connect($db_host . ':' . $db_port, $db_user, $db_pass);
            if (!$conn) {
                $this->assign('error_msg', L('connect_mysql_error'));
                $this->display();
                exit;
            }
            $selected_db = @mysql_select_db($db_name);
            if ($selected_db) {
                //如果数据库存在 并且里面安装过   提示是否覆盖
                $query = @mysql_query("SHOW TABLES LIKE '{$db_prefix}%'");
                $was_install = false;
                while ($row = mysql_fetch_assoc($query)) {
                    $was_install = true;
                    break;
                }
                if ($was_install && !isset($force_install)) {
                    $this->assign('database_name_tip', L('db_isset'));
                    $this->display();
                    exit;
                } else {
                    $this->_set_temp($_POST);
                    $this->redirect('index/install');
                }
            } else {
                if (mysql_get_server_info($conn) > '4.1') {
                    $charset = C('DEFAULT_CHARSET');
                    $sql = "CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET " . str_replace('-', '', $charset);
                } else {
                    $sql = "CREATE DATABASE IF NOT EXISTS `{$db_name}`";
                }
                if (!mysql_query($sql, $conn)) {
                    $this->assign('error_msg', L('create_db_error'));
                    $this->display();
                    exit;
                }
                $this->_set_temp($_POST);
                $this->redirect('index/install');
            }
        } else {
            $this->display();
        }
    }

    /**
     * 安装
     */
    public function install() {
        $this->assign('step_curr', 'install');
        $this->display();
    }

    /**
     * 显示安装进程
     */
    public function show_process() {
        $charset = C('DEFAULT_CHARSET');
        header('Content-type:text/html;charset=' . $charset);
        ob_start();
        $temp_info = include_once(DATA_PATH . 'install.temp.php');
        $conn = mysql_connect($temp_info['db_host'] . ':' . $temp_info['db_port'], $temp_info['db_user'], $temp_info['db_pass']);
        $version = mysql_get_server_info();
        $charset = str_replace('-', '', $charset);
        if ($version > '4.1') {
            if ($charset != 'latin1') {
                mysql_query("SET character_set_connection={$charset}, character_set_results={$charset}, character_set_client=binary", $conn);
            }if ($version > '5.0.1') {
                mysql_query("SET sql_mode=''", $conn);
            }
        }
        $selected_db = mysql_select_db($temp_info['db_name'], $conn);
        //创建数据表
        echo L('create_table_begin') . '<br />';
        $sqls = $this->_get_sql(APP_PATH . '/Sql_data/create_table.sql');
        foreach ($sqls as $sql) {
            //替换前缀
            $sql = str_replace('`pp_', '`' . $temp_info['db_prefix'], $sql);
            //获得表名
            $run = mysql_query($sql, $conn);
            if (substr($sql, 0, 12) == 'CREATE TABLE') {
                $table_name = $temp_info['db_prefix'] . preg_replace("/CREATE TABLE `" . $temp_info['db_prefix'] . "([a-z0-9_]+)` .*/is", "\\1", $sql);
                echo sprintf(L('create_table_successed'), $table_name) . '<br />';
                flush();
                ob_flush();
            }
        }
        //添加默认数据
        echo L('insert_initdate_begin') . '<br />';
        $sqls = $this->_get_sql(APP_PATH . '/Sql_data/initdata.sql');
        //添加管理员帐号
        $admin_pass = md5($temp_info['admin_pass']);
        $add_time = time();
        $sqls[] = "INSERT INTO `" . $temp_info['db_prefix'] . "admin` (`user_name`, `password`, `add_time`, `role_id`) VALUES " .
                "('" . $temp_info['admin_user'] . "', '" . $admin_pass . "', '" . $add_time . "', 1);";
        foreach ($sqls as $sql) {
            //替换前缀
            $sql = str_replace('`pp_', '`' . $temp_info['db_prefix'], $sql);
            //获得表名
            if (substr($sql, 0, 11) == 'INSERT INTO') {
                $table_name = $temp_info['db_prefix'] . preg_replace("/INSERT INTO `" . $temp_info['db_prefix'] . "([a-z0-9_]+)` .*/is", "\\1", $sql);
                $run = mysql_query($sql, $conn);
                echo sprintf(L('insert_initdate_successed'), $table_name) . '<br />';
                flush();
                ob_flush();
            }
        }
        //修改配置文件
        $config_file = ROOT_PATH . '/config.inc.php';
        $config_data = require($config_file);
        $config_data['DB_HOST'] = $temp_info['db_host'];
        $config_data['DB_NAME'] = $temp_info['db_name'];
        $config_data['DB_USER'] = $temp_info['db_user'];
        $config_data['DB_PWD'] = $temp_info['db_pass'];
        $config_data['DB_PORT'] = $temp_info['db_port'];
        $config_data['DB_PREFIX'] = $temp_info['db_prefix'];
        file_put_contents($config_file, "<?php\r\nreturn " . var_export($config_data, true) . "; \r\n?>");
        exit;
    }

    /**
     * 安装完成
     */
    public function finish() {
        $this->assign('step_curr', 'finish');
        //锁定安装程序
        touch(ROOT_PATH . '/data/install.lock');
        $this->display();
        //删除安装目录
        //import("ORG.Io.Dir");
        //Dir::delDir(APP_PATH);
        //unlink('./install.php');
    }

    private function _set_temp($temp_data) {
        $file = DATA_PATH . 'install.temp.php';
        $temp_data = array(
            'db_host' => $temp_data['db_host'],
            'db_port' => $temp_data['db_port'],
            'db_user' => $temp_data['db_user'],
            'db_pass' => $temp_data['db_pass'],
            'db_name' => $temp_data['db_name'],
            'db_prefix' => $temp_data['db_prefix'],
            'admin_user' => $temp_data['admin_user'],
            'admin_pass' => $temp_data['admin_pass'],
            'admin_email' => $temp_data['admin_email'],
        );
        file_put_contents($file, "<?php\r\nreturn " . var_export($temp_data, true) . "; \r\n?>");
    }

    private function _get_sql($sql_file) {
        $contents = file_get_contents($sql_file);
        $contents = str_replace("\r\n", "\n", $contents);
        $contents = trim(str_replace("\r", "\n", $contents));
        $return_items = $items = array();
        $items = explode(";\n", $contents);

        foreach ($items as $item) {
            $return_item = '';
            $item = trim($item);
            $lines = explode("\n", $item);
            foreach ($lines as $line) {
                if (isset($line[1]) && $line[0] . $line[1] == '--') {
                    continue;
                }
                $return_item .= $line;
            }
            if ($return_item) {
                $return_items[] = $return_item; //.";";
            }
        }
        return $return_items;
    }

    public function is_email($email) {
        $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,5}\$/i";
        if (strpos($email, '@') !== false && strpos($email, '.') !== false) {
            if (preg_match($chars, $email)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}