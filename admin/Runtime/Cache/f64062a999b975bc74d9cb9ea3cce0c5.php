<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=7" /><link href="__ROOT__/statics/admin/css/style.css" rel="stylesheet" type="text/css"/><link href="__ROOT__/statics/css/dialog.css" rel="stylesheet" type="text/css" /><script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/jquery-1.4.2.min.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/plugins/formvalidator.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/plugins/formvalidatorregex.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/statics/admin/js/admin_common.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/statics/js/dialog.js"></script><script language="javascript" type="text/javascript" src="__ROOT__/statics/js/iColorPicker.js"></script><script language="javascript">var URL = '__URL__';
var ROOT_PATH = '__ROOT__';
var APP	 =	 '__APP__';
var lang_please_select = "<?php echo (L("please_select")); ?>";
var def=<?php echo ($def); ?>;
$(function($){
	$("#ajax_loading").ajaxStart(function(){
		$(this).show();
	}).ajaxSuccess(function(){
		$(this).hide();
	});
});

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><form id="myform" name="myform" action="<?php echo u('setting/edit');?>" method="post"><div class="pad-10"><div class="col-tab"><ul class="tabBut cu-li"><li id="tab_setting_1" class="on" onclick="SwapTab('setting','on','',3,1);">网站信息</li><li id="tab_setting_2" onclick="SwapTab('setting','on','',3,2);">首页设置</li><li id="tab_setting_3" onclick="SwapTab('setting','on','',3,3);">网站状态</li></ul><div id="div_setting_1" class="contentList pad-10"><table width="100%" cellpadding="2" cellspacing="1" class="table_form"><tr><th width="100">网站名称 :</th><td><input type="text" name="site[site_name]" size="80" value="<?php echo ($set["site_name"]); ?>"></td></tr><tr><th width="100">网站域名 :</th><td><input type="text" name="site[site_domain]" size="80" value="<?php echo ($set["site_domain"]); ?>"></td></tr><tr><th>网站标题 :</th><td><input type="text" name="site[site_title]" size="80" value="<?php echo ($set["site_title"]); ?>"></td></tr><tr><th>网站关键字 :</th><td><input type="text" name="site[site_keyword]" size="80" value="<?php echo ($set["site_keyword"]); ?>"></td></tr><tr><th>网站描述 :</th><td><textarea rows="3" cols="80" name="site[site_description]"><?php echo ($set["site_description"]); ?></textarea></td></tr><tr><th>默认搜索关键字 :</th><td><input type="text" name="site[default_kw]" id="site_icp" class="input-text" value="<?php echo ($set["default_kw"]); ?>"></td></tr><tr><th>热门搜索:</th><td><textarea rows="2" cols="80" name="site[search_words]" id="search_words"><?php echo ($set["search_words"]); ?></textarea></td></tr><tr><th>ICP证书号 :</th><td><input type="text" name="site[site_icp]" id="site_icp" class="input-text" value="<?php echo ($set["site_icp"]); ?>"></td></tr><tr><th>统计代码 :</th><td><textarea rows="4" cols="80" name="site[statistics_code]" id="statistics_code"><?php echo ($set["statistics_code"]); ?></textarea></td></tr></table></div><div id="div_setting_2" class="contentList pad-10 hidden"><table width="100%" cellpadding="2" cellspacing="1" class="table_form"><tr><th width="100">是否显示专辑 :</th><td><input type="radio" <?php if($set["index_album"] == '1'): ?>checked="checked"<?php endif; ?> onclick="" value="1" name="site[index_album]"> 显示 &nbsp;&nbsp;
                <input type="radio" <?php if($set["index_album"] == '0'): ?>checked="checked"<?php endif; ?> onclick="" value="0" name="site[index_album]"> 不显示 &nbsp;&nbsp;
              </td></tr><tr><th width="100">是否显示布瀑流 :</th><td><input type="radio" <?php if($set["index_pins"] == '1'): ?>checked="checked"<?php endif; ?> onclick="" value="1" name="site[index_pins]"> 显示 &nbsp;&nbsp;
                <input type="radio" <?php if($set["index_pins"] == '0'): ?>checked="checked"<?php endif; ?> onclick="" value="0" name="site[index_pins]"> 不显示 &nbsp;&nbsp;
              </td></tr></table></div><div id="div_setting_3" class="contentList pad-10 hidden"><table width="100%" cellpadding="2" cellspacing="1" class="table_form"><tr><th width="100">网站状态 :</th><td><input type="radio" <?php if($set["site_status"] == '1'): ?>checked="checked"<?php endif; ?> onclick="" value="1" name="site[site_status]"> 开启 &nbsp;&nbsp;
                <input type="radio" <?php if($set["site_status"] == '0'): ?>checked="checked"<?php endif; ?> onclick="" value="0" name="site[site_status]"> 关闭 &nbsp;&nbsp;
              </td></tr><tr><th>关闭说明 :</th><td><textarea rows="4" cols="80" name="site[closed_reason]"><?php echo ($set["closed_reason"]); ?></textarea></td></tr></table></div><div class="bk15"></div><div class="btn"><input type="submit" value="<?php echo (L("submit")); ?>" onclick="return submitFrom();" name="dosubmit" class="button" id="dosubmit"></div></div></div></form><script type="text/javascript">function SwapTab(name,cls_show,cls_hide,cnt,cur){
    for(i=1;i<=cnt;i++){
		if(i==cur){
			 $('#div_'+name+'_'+i).show();
			 $('#tab_'+name+'_'+i).attr('class',cls_show);
		}else{
			 $('#div_'+name+'_'+i).hide();
			 $('#tab_'+name+'_'+i).attr('class',cls_hide);
		}
	}
}

</script></body></html>