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

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><div class="pad-lr-10"><form name="searchform" action="" method="get" ><table width="100%" cellspacing="0" class="search-form"><tbody><tr><td><div class="explain-col">				链接分类 :<select name="cate_id" style="width:140px;"><option value="" <?php if($cate_id == ''): ?>selected="selected"<?php endif; ?>>--所有分类--</option><?php if(is_array($flink_cate_list)): $i = 0; $__LIST__ = $flink_cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($cate_id == $val['id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select>            	关键字 :
                <input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($keyword); ?>" /><input type="hidden" name="m" value="flink" /><input type="submit" name="search" class="button" value="搜索" /></div></td></tr></tbody></table></form><form id="myform" name="myform" action="<?php echo u('flink/del');?>" method="post" onsubmit="return check();"><div class="table-list"><table width="100%" cellspacing="0"><thead><tr><th width=50>序号</th><th width=20><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th><th width=40>ID</th><th width="40">图片</th><th width=200>链接名称</th><th align=left>&nbsp;&nbsp;&nbsp;&nbsp;链接地址</th><th width=150>所属分类</th><th width="60">排序值</th><th width="40">审核</th><th width="60">操作</th></tr></thead><tbody><?php if(is_array($link_list)): $i = 0; $__LIST__ = $link_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr><td align="center"><?php echo ($val["key"]); ?></td><td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]"></td><td align="center"><?php echo ($val["id"]); ?></td><td align="center"><?php if($val['img'] != ''): ?><img src="__ROOT__/data/flink/<?php echo ($val["img"]); ?>" height="30px"/><?php endif; ?></td><td align="center"><?php echo ($val["name"]); ?></td><td align="left">&nbsp;&nbsp;&nbsp;<font color="red"><?php echo ($val["url"]); ?></font></td><td align="center"><b><?php echo ($val["cate_name"]); ?></b></td><td align="center"><input type="text" class="input-text-c input-text" value="<?php echo ($val["ordid"]); ?>" size="4" name="listorders[<?php echo ($val["id"]); ?>]"></td><td align="center" onclick="status(<?php echo ($val["id"]); ?>,'status')" id="status_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["status"]); ?>.gif"</td><td align="center"><a href="javascript:edit(<?php echo ($val["id"]); ?>,'<?php echo ($val["name"]); ?>')"><em class="blue"><?php echo (L("edit")); ?></em></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table><div class="btn"><label for="check_box" style="float:left;"><?php echo (L("select_all")); ?>/<?php echo (L("cancel")); ?></label><input type="submit" class="button" name="dosubmit" value="<?php echo (L("delete")); ?>" onclick="return confirm('<?php echo (L("sure_delete")); ?>')" style="float:left;margin:0 10px 0 10px;"/><input type="submit" class="button" name="dosubmit" onclick="document.myform.action='<?php echo u("flink/ordid");?>'" value="排序" style="float:left;"/><div id="pages"><?php echo ($page); ?></div></div></div></form></div><script type="text/javascript">function edit(flink_id, name) {
	var lang_edit = "<?php echo (L("edit")); ?>";
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:lang_edit+'--'+name,id:'edit',iframe:'?m=flink&a=edit&id='+flink_id,width:'500',height:'250'}, 
		function(){
			var d = window.top.art.dialog({id:'edit'}).data.iframe;
			d.document.getElementById('dosubmit').click();
			return false;
		}, 
		function(){
			window.top.art.dialog({id:'edit'}).close();
		}
	);
}

var lang_name = "友情链接";
function check(){
	if($("#myform").attr('action') != '<?php echo u("flink/ordid");?>') {
		var ids='';
		$("input[name='id[]']:checked").each(function(i, n){
			ids += $(n).val() + ',';
		});
		if(ids=='') {
			window.top.art.dialog({content:lang_please_select+lang_name,lock:true,width:'200',height:'50',time:1.5},function(){});
			return false;
		}
	}
	return true;
}

function status(id,type){
    $.get("<?php echo u('flink/status');?>", { id: id, type: type }, function(jsondata){
		var return_data  = eval("("+jsondata+")");
		$("#"+type+"_"+id+" img").attr('src', '__ROOT__/statics/images/status_'+return_data.data+'.gif');
	}); 
}
</script></body></html>