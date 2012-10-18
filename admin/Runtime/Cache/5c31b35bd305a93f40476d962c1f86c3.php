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

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><link rel="stylesheet" type="text/css" href="__ROOT__/statics/js/calendar/calendar-blue.css"/><script type="text/javascript" src="__ROOT__/statics/js/calendar/calendar.js"></script><div class="pad-10" ><form name="searchform" action="" method="get" ><table width="100%" cellspacing="0" class="search-form"><tbody><tr><td><div class="explain-col">            	发布时间：
            	<input type="text" name="time_start" id="time_start" class="date" size="12" value="<?php echo ($time_start); ?>"><script language="javascript" type="text/javascript">                    Calendar.setup({
                        inputField     :    "time_start",
                        ifFormat       :    "%Y-%m-%d",
                        showsTime      :    'true',
                        timeFormat     :    "24"
                    });
                </script>                -
                <input type="text" name="time_end" id="time_end" class="date" size="12" value="<?php echo ($time_end); ?>"><script language="javascript" type="text/javascript">                    Calendar.setup({
                        inputField     :    "time_end",
                        ifFormat       :    "%Y-%m-%d",
                        showsTime      :    'true',
                        timeFormat     :    "24"
                    });
                </script><select name="status"><option value="-1">-是否审核-</option><option value="1" <?php if($status == 1): ?>selected="selected"<?php endif; ?>>已审核</option><option value="0" <?php if($status == 0): ?>selected="selected"<?php endif; ?>>未审核</option></select>                &nbsp;关键字 :
                <input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($keyword); ?>" /><input type="hidden" name="m" value="items" /><input type="hidden" name="a" value="comments" /><input type="submit" name="search" class="button" value="搜索" /></div></td></tr></tbody></table></form><form id="myform" name="myform" action="<?php echo u('items/comments_delete');?>" method="post" onsubmit="return check();"><div class="table-list"><table width="100%" cellspacing="0"><thead><tr><th width=40><?php echo (L("orders")); ?></th><th width=15><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th><th width="40">ID</th><th width="40">&nbsp;</th><th>商品评论</th><th width=120>发布时间</th><th width=30>审核</th></tr></thead><tbody><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr><td align="center"><?php echo ($val["key"]); ?></td><td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]"></td><td align="center"><a class="blue" href="./?a=index&m=item&id=<?php echo ($val['pid']); ?>#comments" target=_blank><?php echo ($val["id"]); ?></a></td><td align="right"><a class="blue" href="./?a=index&m=item&id=<?php echo ($val['pid']); ?>#comments" target=_blank><img src="<?php echo ($val["items_img"]); ?>" width="35" width="35" class="preview" bimg="<?php echo ($val["items_img"]); ?>"></a></td><td align="left"><a href="./?a=index&m=item&id=<?php echo ($val['pid']); ?>#comments" target=_blank><?php echo ($val["title"]); ?></a><br><font color=red><?php echo ($val["info"]); ?></font></td><td align="center"><em style="color:green;"><?php echo date("Y-n-j   H:i:s",$val["add_time"]);?></em></td><td align="center" onclick="status(<?php echo ($val["id"]); ?>,'comments_status')" id="comments_status_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["status"]); ?>.gif" /></td><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table><div class="btn"><label for="check_box" style="float:left;">全选/取消</label><input type="submit" class="button" name="dosubmit" value="<?php echo (L("delete")); ?>" onclick="return confirm('<?php echo (L("sure_delete")); ?>')" style="float:left;margin:0 10px 0 10px;"/><div id="pages"><?php echo ($page); ?></div></div></div></form></div><script language="javascript">function status(id,type){
    $.get("<?php echo u('items/comments_status');?>", { id: id, type: type }, function(jsondata){
		var return_data  = eval("("+jsondata+")");
		$("#"+type+"_"+id+" img").attr('src', '__ROOT__/statics/images/status_'+return_data.data+'.gif')
	}); 
}
</script></body></html>