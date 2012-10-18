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

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><link rel="stylesheet" type="text/css" href="__ROOT__/statics/js/calendar/calendar-blue.css"/><script type="text/javascript" src="__ROOT__/statics/js/calendar/calendar.js"></script><script type="text/javascript" src="__ROOT__/statics/js/jquery/plugins/jquery.imagePreview.js"></script><div class="pad-10" ><form name="searchform" action="" method="get" ><table width="100%" cellspacing="0" class="search-form"><tbody><tr><td><div class="explain-col">            	发布时间：
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
                </script>            	&nbsp;商品分类：
                <select name="cate_id"><option value="0">--请选择分类--</option><?php if(is_array($cate_list)): $i = 0; $__LIST__ = $cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" level="<?php echo ($val["level"]); ?>" <?php if($cate_id == $val['id']): ?>selected="selected"<?php endif; ?>><?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$val['level']); echo trim($val['name']);?></option><?php endforeach; endif; else: echo "" ;endif; ?></select>                &nbsp;
                <select name="is_index"><option value="-1">-首页显示-</option><option value="1" <?php if($is_index == 1): ?>selected="selected"<?php endif; ?>>是</option><option value="0" <?php if($is_index == 0): ?>selected="selected"<?php endif; ?>>否</option></select>				 &nbsp;
                <select name="status"><option value="-1">-是否审核-</option><option value="1" <?php if($status == 1): ?>selected="selected"<?php endif; ?>>已审核</option><option value="0" <?php if($status == 0): ?>selected="selected"<?php endif; ?>>未审核</option></select>                &nbsp;关键字 :
                <input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($keyword); ?>" /><input type="hidden" name="m" value="items" /><input type="submit" name="search" class="button" value="搜索" /></div></td></tr></tbody></table></form><form id="myform" name="myform" action="<?php echo u('items/delete');?>" method="post" onsubmit="return check();"><div class="table-list"><table width="100%" cellspacing="0"><thead><tr><th width=40><?php echo (L("orders")); ?></th><th width=15><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th><th width="40">ID</th><th width="40">&nbsp;</th><th>商品名称</th><th width=60>分类</th><th width=30>来源</th><th width=80><a href="<?php echo u('items/index',array('time_start'=>$time_start,'time_end'=>$time_end,'cate_id'=>$cate_id,'is_index'=>$is_index,'keyword'=>$keyword,'order'=>'last_time','sort'=>$sort));?>" class="blue <?php if($order == 'last_time'): ?>order_sort_<?php if($sort == 'desc'): ?>1<?php else: ?>0<?php endif; endif; ?>">更新时间</a></th><th width=60><a href="<?php echo u('items/index',array('time_start'=>$time_start,'time_end'=>$time_end,'cate_id'=>$cate_id,'is_index'=>$is_index,'keyword'=>$keyword,'order'=>'price','sort'=>$sort));?>" class="blue <?php if($order == 'price'): ?>order_sort_<?php if($sort == 'desc'): ?>1<?php else: ?>0<?php endif; endif; ?>">价格(元)</a></th><th width=40><a href="<?php echo u('items/index',array('time_start'=>$time_start,'time_end'=>$time_end,'cate_id'=>$cate_id,'is_index'=>$is_index,'keyword'=>$keyword,'order'=>'likes','sort'=>$sort));?>" class="blue <?php if($order == 'likes'): ?>order_sort_<?php if($sort == 'desc'): ?>1<?php else: ?>0<?php endif; endif; ?>">喜欢</a></th><th width=40><a href="<?php echo u('items/index',array('time_start'=>$time_start,'time_end'=>$time_end,'cate_id'=>$cate_id,'is_index'=>$is_index,'keyword'=>$keyword,'order'=>'hits','sort'=>$sort));?>" class="blue <?php if($order == 'hits'): ?>order_sort_<?php if($sort == 'desc'): ?>1<?php else: ?>0<?php endif; endif; ?>">人气</a></th><th width="30">排序</th><th width=30>首页</th><th width=30>审核</th><th width=30>操作</th></tr></thead><tbody><?php if(is_array($items_list)): $i = 0; $__LIST__ = $items_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr><td align="center"><?php echo ($val["key"]); ?></td><td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]"></td><td align="center"><a class="blue" href="index.php?a=index&m=item&id=<?php echo ($val['id']); ?>" target=_blank><?php echo ($val["id"]); ?></a></td><td align="right"><img src="<?php echo ($val["simg"]); ?>" width="35" width="35" class="preview" bimg="<?php echo ($val["img"]); ?>"></td><td align="left"><a href="<?php echo ($val["url"]); ?>" target=_blank><?php echo ($val["title"]); ?></a></td><td align="center"><b><?php echo ($val["items_cate"]["name"]); ?></b></td><td align="center"><img src="__ROOT__/data/author/32_<?php echo ($val["items_site"]["site_logo"]); ?>" width="16" height="16" style="margin-right:5px;" /></td><td align="center"><?php echo (date("Y-m-d H:i:s",$val["last_time"])); ?></td><td align="center"><em style="color:red;">￥<?php echo ($val["price"]); ?></em></td><td><input type="text" class="input-text-c input-text" value="<?php echo ($val["likes"]); ?>" size="4" name="likes_<?php echo ($val["id"]); ?>"  id="likes_<?php echo ($val["id"]); ?>" onchange="likes(<?php echo ($val["id"]); ?>)" onkeyup="this.value=this.value.replace(/D/g,'')" onafterpaste="this.value=this.value.replace(/D/g,'')"></td><td align="center"><em style="color:green;"><?php echo ($val["hits"]); ?></em></td><td><input type="text" class="input-text-c input-text" value="<?php echo ($val["sort_order"]); ?>" size="4" name="listorders[<?php echo ($val["id"]); ?>]"  id="listorders[<?php echo ($val["id"]); ?>]" onkeyup="this.value=this.value.replace(/D/g,'')" onafterpaste="this.value=this.value.replace(/D/g,'')"></td><td align="center" onclick="status(<?php echo ($val["id"]); ?>,'is_index')" id="is_index_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["is_index"]); ?>.gif" /></td><td align="center" onclick="status(<?php echo ($val["id"]); ?>,'status')" id="status_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["status"]); ?>.gif" /></td><td align="center"><a class="blue" href="<?php echo u('items/edit',array('id'=>$val['id']));?>">编辑</a></td><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table><div class="btn"><label for="check_box" style="float:left;">全选/取消</label><input type="submit" class="button" name="dosubmit" value="<?php echo (L("delete")); ?>" onclick="return confirm('<?php echo (L("sure_delete")); ?>')" style="float:left;margin:0 10px 0 10px;"/><input type="submit" class="button" name="dosubmit" onclick="document.myform.action='<?php echo u(MODULE_NAME."/sort_order");?>'" value="<?php echo (L("sort_order")); ?>"/><input type="submit" class="button" name="dosubmit" onclick="document.myform.action='<?php echo u(MODULE_NAME."/update");?>'" value="更新"/><div id="pages"><?php echo ($page); ?></div></div></div></form></div><script language="javascript">$(function(){
	$(".preview").preview();
});

var lang_cate_name = "商品名称";
function check(){
	if($("#myform").attr('action') == '<?php echo u("items/delete");?>') {
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
    $.get("<?php echo u('items/status');?>", { id: id, type: type }, function(jsondata){
		var return_data  = eval("("+jsondata+")");
		$("#"+type+"_"+id+" img").attr('src', '__ROOT__/statics/images/status_'+return_data.data+'.gif')
	}); 
}
function likes(id){
	var likes	= $('#likes_'+id).val();
	$.get("<?php echo u('items/likes');?>", { id: id, likes: likes }, function(json){
		//alert(likes);
	}); 
}
</script></body></html>