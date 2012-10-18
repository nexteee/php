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

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><div class="pad-10" ><form id="myform" name="myform" action="<?php echo u('items_cate/delete');?>" method="post" onsubmit="return check();"><div class="table-list"><table width="100%" cellspacing="0"><thead><tr><th width="4%"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th><th width="200">分类名称</th><th width="30">图片</th><th width="80">分类ID</th><th width="80">商品数</th><th>SEO Title</th><th width="60">排序值</th><th width="60">首页显示</th><th width="40">状态</th><th width="120">操作</th></tr></thead><tbody><?php if(is_array($items_cate_list)): $i = 0; $__LIST__ = $items_cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr class="<?php echo ($val["cls"]); ?>" iid="<?php echo ($val["id"]); ?>" pid="<?php echo ($val["pid"]); ?>" level="<?php echo ($val["level"]); ?>"><td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]"></td><td><div style="margin-left:<?php echo ($val['level']*30); ?>px"><img src="__ROOT__/statics/admin/images/tv-collapsable.gif" class="expandable" id="<?php echo ($val["id"]); ?>" pid="<?php echo ($val["pid"]); ?>" level="<?php echo ($val["level"]); ?>"/><span style="color:<?php echo ($val["color"]); ?>;"><?php echo ($val["name"]); ?></span></div></td><td><?php if($val['img'] != ''): ?><img src="__ROOT__/data/items_cate/<?php echo ($val["img"]); ?>" height="25px;"/><?php endif; ?></td><td align="center"><?php echo ($val["id"]); ?></td><td align="center"><?php echo ($val["item_nums"]); ?></td><td align="left"><?php echo ($val["seo_title"]); ?></td><td align="center"><input type="text" class="input-text-c input-text" value="<?php echo ($val["ordid"]); ?>" size="4" name="listorders[<?php echo ($val["id"]); ?>]"  id="listorders[<?php echo ($val["id"]); ?>]" onkeyup="this.value=this.value.replace(/D/g,'')" onafterpaste="this.value=this.value.replace(/D/g,'')"></td><td align="center" onclick="status(<?php echo ($val["id"]); ?>,'is_hots')" id="is_hots_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["is_hots"]); ?>.gif" /></td><td align="center" onclick="status(<?php echo ($val["id"]); ?>,'status')" id="status_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["status"]); ?>.gif" /></td><td align="center"><a class="blue" href="<?php echo u('items_cate/edit',array('id'=>$val['id']));?>">编辑</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table><div class="btn"><label for="check_box">全选/取消</label><input type="submit" class="button" name="dosubmit" value="<?php echo (L("delete")); ?>" onclick="return confirm('<?php echo (L("sure_delete")); ?>')"/><input type="submit" class="button" name="dosubmit" onclick="document.myform.action='<?php echo u("items_cate/sort_order");?>'" value="<?php echo (L("sort_order")); ?>"/></div></div></form></div><script type="text/javascript">$(function(){
	$('.expandable').toggle(
		function(){
			var id=
			$('.sub_'+$(this).attr('id')).hide();		
			$(this).attr('src',ROOT_PATH+'/statics/admin/images/tv-expandable.gif');
		},
		function(){
			$('.sub_'+$(this).attr('id')).show();
			$(this).attr('src',ROOT_PATH+'/statics/admin/images/tv-collapsable.gif');
		}
	);
});

var lang_items_cate_name = "商品分类";
function check(){
	if($("#myform").attr('action') == '<?php echo u("items_cate/delete");?>') {
		var ids='';
		$("input[name='id[]']:checked").each(function(i, n){
			ids += $(n).val() + ',';
		});

		if(ids=='') {
			window.top.art.dialog({content:lang_please_select+lang_items_cate_name,lock:true,width:'200',height:'50',time:1.5},function(){});
			return false;
		}
	}
	return true;
}
function status(id,type){
    $.get("<?php echo u('items_cate/status');?>", { id: id, type: type }, function(jsondata){
		var return_data  = eval("("+jsondata+")");
		$("#"+type+"_"+id+" img").attr('src', '__ROOT__/statics/images/status_'+return_data.data+'.gif')
	}); 
}
</script></body></html>