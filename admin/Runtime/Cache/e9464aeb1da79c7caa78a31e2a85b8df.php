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

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/jquery.article_cate.js"></script><div class="pad-lr-10" ><form id="myform" name="myform" action="<?php echo u('article_cate/delete');?>" method="post" onsubmit="return check();"><div class="table-list"><table width="100%" cellspacing="0"><thead><tr><th width="4%"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th><th width="250">分类名称</th><th width="80">分类别名</th><th width="80">分类ID</th><th align="left">SEO Title</th><th width="80">资讯数</th><th width="60">排序值</th><th width="60">审核</th><th width="40">操作</th></tr></thead><tbody><?php if(is_array($article_cate_list['parent'])): $i = 0; $__LIST__ = $article_cate_list['parent'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr><td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]"></td><td>&nbsp;&nbsp;<img src="__ROOT__/statics/admin/images/tv-expandable.gif" />&nbsp;&nbsp;<?php echo ($val["name"]); ?></td><td align="center"><em style="color:blue;"><?php echo ($val["alias"]); ?><em style="color:red;"></td><td align="center"><em style="color:green;"><?php echo ($val["id"]); ?></em></td><td align="left"><?php echo ($val["seo_title"]); ?></td><td align="center"><em style="color:red;"><?php echo ($val["article_nums"]); ?></em></td><td align="center"><input type="text" class="input-text-c input-text" value="<?php echo ($val["sort_order"]); ?>" size="4" name="listorders[<?php echo ($val["id"]); ?>]"></td><td align="center" onclick="status(<?php echo ($val["id"]); ?>,'status')" id="status_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["status"]); ?>.gif"</td><td align="center"><a class="blue" href="javascript:edit(<?php echo ($val["id"]); ?>,'<?php echo ($val["name"]); ?>')"><?php echo (L("edit")); ?></a></td></tr><?php if(is_array($article_cate_list['sub'][$val['id']])): $i = 0; $__LIST__ = $article_cate_list['sub'][$val['id']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sval): $mod = ($i % 2 );++$i;?><tr><td align="center"><input type="checkbox" value="<?php echo ($sval["id"]); ?>" name="id[]"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="__ROOT__/statics/admin/images/tv-collapsable.gif" />&nbsp;&nbsp;<?php echo ($sval["name"]); ?></td><td align="center"><em style="color:blue;"><?php echo ($sval["alias"]); ?><em style="color:red;"></td><td align="center"><em style="color:green;"><?php echo ($sval["id"]); ?></em></td><td align="left"><?php echo ($sval["seo_title"]); ?></td><td align="center"><em style="color:red;"><?php echo ($sval["article_nums"]); ?></em></td><td align="center"><input type="text" class="input-text-c input-text" value="<?php echo ($sval["sort_order"]); ?>" size="4" name="listorders[<?php echo ($sval["id"]); ?>]"></td><td align="center" onclick="status(<?php echo ($sval["id"]); ?>,'status')" id="status_<?php echo ($sval["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($sval["status"]); ?>.gif"</td><td align="center"><a class="blue" href="javascript:edit(<?php echo ($sval["id"]); ?>,'<?php echo ($sval["name"]); ?>')"><?php echo (L("edit")); ?></a></td></tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?></tbody></table><div class="btn"><label for="check_box"><?php echo (L("select_all")); ?>/<?php echo (L("cancel")); ?></label><input type="submit" class="button" name="dosubmit" value="<?php echo (L("delete")); ?>" onclick="return confirm('<?php echo (L("sure_delete")); ?>')"/><input type="submit" class="button" name="dosubmit" onclick="document.myform.action='<?php echo u("article_cate/sort_order");?>'" value="<?php echo (L("sort_order")); ?>"/></div></div></form></div><script type="text/javascript">function edit(id, name) {
	var lang_edit = "<?php echo (L("edit")); ?>";
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:lang_edit+'--'+name,id:'edit',iframe:'?m=article_cate&a=edit&id='+id,width:'500',height:'420'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;d.document.getElementById('dosubmit').click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
var lang_cate_name = "<?php echo (L("article_cate_name")); ?>";
function check(){
	if($("#myform").attr('action') != '<?php echo u("article_cate/sort_order");?>') {
		var ids='';
		$("input[name='id[]']:checked").each(function(i, n){
			ids += $(n).val() + ',';
		});

		if(ids=='') {
			window.top.art.dialog({content:lang_please_select+lang_cate_name,lock:true,width:'200',height:'50',time:1.5},function(){});
			return false;
		}
	}
	return true;
}
function status(id,type){
    $.get("<?php echo u('article_cate/status');?>", { id: id, type: type }, function(jsondata){
		var return_data  = eval("("+jsondata+")");
		$("#"+type+"_"+id+" img").attr('src', '__ROOT__/statics/images/status_'+return_data.data+'.gif')
	}); 
}
</script></body></html>