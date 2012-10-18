<?php
class articleAction extends baseAction
{
	public function index()
	{
	    $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error("404");
	    $article_mod = D('article');
	    $art_info = $article_mod->find($id);
	    $art_list = $article_mod->where('cate_id='.$art_info['cate_id'])->limit('0,10')->select();

	    $this->assign('art_info', $art_info);
	    $this->assign('art_list', $art_list);

	    $this->seo['seo_title'] = !empty($art_info['seo_title']) ? $art_info['seo_title'] : $art_info['title'];
	    $this->seo['seo_title'] = $this->seo['seo_title'] . ' - ' . $this->setting['site_name'];
	    $this->seo['seo_keys'] = !empty($art_info['seo_keys']) ? $art_info['seo_keys'] : $art_info['title'];
	    $art_info['seo_desc'] && $this->seo['seo_desc'] = $art_info['seo_desc'];
	    $this->assign('seo',$this->seo);
	    $this->display();
	}
}