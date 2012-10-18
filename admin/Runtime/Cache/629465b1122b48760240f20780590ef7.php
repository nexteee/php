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

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><form action="<?php echo u('focus/insert');?>" method="post" name="myform" id="myform"  enctype="multipart/form-data" style="margin-top:10px;"><div class="pad-10"><div class="col-tab"><div id="div_setting_1" class="contentList pad-10"><table width="100%" cellpadding="2" cellspacing="1" class="table_form"><tr><th>所属分类 :</th><td><select name="cate_id" id="CATE_ID"><?php if(is_array($cate_list['parent'])): $i = 0; $__LIST__ = $cate_list['parent'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>"><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></td></tr><tr><th width="100">焦点名称 :</th><td><input type="text" name="title" id="title" class="input-text" size="60"></td></tr><tr><th width="100">链接地址 :</th><td><input type="text" name="url" id="url" class="input-text" size="60"></td></tr><tr><th>焦点图片：</th><td><input type="file" name="img" id="img" class="input-text" size=21 /></td></tr><tr><th>排序值 :</th><td><input type="text" name="ordid" id="ordid" class="input-text" size="8" value="<?php echo ($article["ordid"]); ?>" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"></td></tr><tr><th>资讯摘要 :</th><td><textarea name="abst" id="abst" style="width:50%;height:70px;"></textarea></td></tr><tr><th>审核状态 :</th><td><input type="radio" name="status" class="radio_style" value="1">&nbsp;已审核&nbsp;&nbsp;&nbsp;
				<input type="radio" name="status" class="radio_style" value="0" checked="checked">&nbsp;未审核&nbsp;&nbsp;&nbsp;
			</td></tr><tr></table></div><div class="bk15"></div><div class="btn"><input type="submit" value="<?php echo (L("submit")); ?>" onclick="return submitFrom();" name="dosubmit" class="button" id="dosubmit"></div></div></div></form><script type="text/javascript">function submitFrom(){
	if($("cate_id").val()==0)
	{
	   alert('请选择文章分类');
	   return false;
	}

}
</script></body></html>