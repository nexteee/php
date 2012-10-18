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

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><div class="pad-10" ><form name="searchform" action="<?php echo u(MODULE_NAME.'/'.ACTION_NAME);?>" method="post" ><table width="100%" cellspacing="0" class="search-form"><tbody><tr><td><div class="explain-col">            关键字:
            <input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($keyword); ?>" /><?php if(is_array($select_list)): $i = 0; $__LIST__ = $select_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><select name="<?php echo ($val["name"]); ?>" id="<?php echo ($val["name"]); ?>"><?php if(is_array($val['items'])): $i = 0; $__LIST__ = $val['items'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sval): $mod = ($i % 2 );++$i;?><option value="<?php echo ($sval["id"]); ?>"><?php echo ($sval["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select><?php endforeach; endif; else: echo "" ;endif; ?><input type="submit" name="search" class="button" value="搜索" /></div></td></tr></tbody></table></form><script type="text/javascript">$(function(){ 
	<?php if(is_array($select_list)): $i = 0; $__LIST__ = $select_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>$('#<?php echo ($val["name"]); ?>').val(def.request.<?php echo ($val["name"]); ?>);<?php endforeach; endif; else: echo "" ;endif; ?>});
</script><form id="myform" name="myform" action="<?php echo u(MODULE_NAME.'/delete');?>" method="post" onsubmit="return check();"><div class="table-list"><table width="100%" cellspacing="0"><thead><tr><th width=15><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th><th width="40">ID</th><th>名称</th><th>分类</th><th>用户</th><th width=120>添加时间</th><th width="30">排序</th><th width=30>推荐</th><th width=30>审核</th></tr></thead><tbody><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr><td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]"></td><td><?php echo ($val["id"]); ?></td><td align="center"><em class="blue"><?php echo ($val["title"]); ?></em></td><td align="center"><?php echo ($val["cate"]["title"]); ?></td><td  align="center"><a href="<?php echo u('user/index',array('keyword'=>$val['user']['name']));?>" target="_blank"><?php echo ($val["user"]["name"]); ?></a></td><td align="center"><?php echo date("Y-n-j   H:i:s",$val["add_time"]);?></td><td><input type="text" class="input-text-c input-text" value="<?php echo ($val["sort_order"]); ?>" size="4" name="listorders[<?php echo ($val["id"]); ?>]"  id="listorders[<?php echo ($val["id"]); ?>]" onkeyup="this.value=this.value.replace(/D/g,'')" onafterpaste="this.value=this.value.replace(/D/g,'')"></td><td align="center" onclick="status(<?php echo ($val["id"]); ?>,'recommend')" id="recommend_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["recommend"]); ?>.gif" /></td><td align="center" onclick="status(<?php echo ($val["id"]); ?>,'status')" id="status_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["status"]); ?>.gif" /></td><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table><div class="btn"><label for="check_box" style="float:left;">全选/取消</label><input type="submit" class="button" name="dosubmit" value="<?php echo (L("delete")); ?>" onclick="return confirm('<?php echo (L("sure_delete")); ?>')" style="float:left;margin:0 10px 0 10px;"/><input type="submit" class="button" name="dosubmit" value="排序" 
        onclick="document.myform.action='<?php echo u(MODULE_NAME."/sort_order");?>'"
        style="float:left;margin:0 10px 0 10px;"/><div id="pages"><?php echo ($page); ?></div></div></div></form></div><script type="text/javascript">function check(){
	if($("#myform").attr('action') == '<?php echo u(MODULE_NAME."/delete");?>') {
		var ids='';
		$("input[name='id[]']:checked").each(function(i, n){
			ids += $(n).val() + ',';
		});
		if(ids=='') {
			window.top.art.dialog({content:lang_please_select,lock:true,width:'200',height:'50',time:1.5},function(){});
			return false;
		}
	}
	return true;
}
function status(id,type){
    $.get("<?php echo u(MODULE_NAME.'/status');?>", { id: id, type: type }, function(jsondata){
		var return_data  = eval("("+jsondata+")");
		$("#"+type+"_"+id+" img").attr('src', '__ROOT__/statics/images/status_'+return_data.data+'.gif');
	}); 
}
</script></body></html>