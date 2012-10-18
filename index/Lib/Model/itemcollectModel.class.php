<?php
class itemcollectModel
{
    public function url_parse($url)
    {
    	//exit("parse go");
        $rs = preg_match("/^(http:\/\/|https:\/\/)/", $url, $match);
		if (intval($rs)==0) {
			$url = "http://".$url;
		}
		$rs= parse_url($url);

		$scheme = isset($rs['scheme']) ? $rs['scheme']."://" : "http://";
		$host = isset($rs['host']) ? $rs['host'] : "none";
        $host = explode('.',$host);
        $host = array_slice($host,-2,2);
        $domain = implode('.',$host);
        $items_site_mod = D('items_site');
        $class = $items_site_mod->where("site_domain like '%".$domain."%'")->getField('alias');

		$file = ROOT_PATH . '/includes/itemcollect/'.$class.'_itemcollect.class.php';
		$file = ROOT_PATH . '/includes/itemcollect/taobao_itemcollect.class.php';
		//exit($file);
		if (file_exists($file)) {
			require_once $file;
			//$class_name = $class."_itemcollect";
			$class_name ="taobao_itemcollect";
			if (class_exists($class_name)) {
				$this->collect_module = new $class_name;
			}
		}
		$this->url = $url;
    }

    /**
	 * 返回结果为false时采集失败
	 */
	public function fetch()
	{
		if ($this->collect_module) {
			return $this->collect_module->fetch($this->url);
		} else {
		    return false;
		}
	}
}