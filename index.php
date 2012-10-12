<?php
defined('ROOT_PATH') or define('ROOT_PATH', dirname(__FILE__));
if (!is_file(ROOT_PATH . '/data/install.lock')) {
    header('Location: ./install.php');
    exit;
}
define('THINK_PATH', './includes/thinkphp/');
define('APP_NAME', 'index');
define('APP_PATH', './index/');
require( THINK_PATH."ThinkPHP.php");
