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

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><div class="pad-10"><table width="100%" cellspacing="0" class="search-form"><tbody><tr><td><div class="explain-col">                分类名称
                <select onchange="get_child_cates(this)"><option value="">--请选择--</option><?php if(is_array($items_cate_list['parent'])): $i = 0; $__LIST__ = $items_cate_list['parent'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><optgroup label="<?php echo ($val["name"]); ?>"></optgroup><?php if(is_array($items_cate_list['sub'][$val['id']])): $i = 0; $__LIST__ = $items_cate_list['sub'][$val['id']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sval): $mod = ($i % 2 );++$i;?><option value="<?php echo ($sval["id"]); ?>" <?php if($item_pid == $sval['id']): ?>selected="selected"<?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($sval["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?></select>&nbsp;&nbsp;&nbsp;<font color=red>(注：该采集是采集阿里妈媽里的商品信息@^@)</font></div></td></tr></tbody></table><?php if($code == 'taobao'): ?><div class="table-list"><form action="" method="post"><table width="100%" cellspacing="0"><thead><tr><th>序号</th><th>分类</th><th>商品数量</th><th>被喜欢数</th><th>采集时间</th><th>操作</th></tr></thead><tbody id="items_cate_list"><?php if(!empty($three_cate_lists)): if(is_array($three_cate_lists)): $i = 0; $__LIST__ = $three_cate_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr><td align="center"><?php echo ($i); ?></td><td align="center" style="padding-left:10px;">&nbsp;&nbsp;<?php echo ($val['name']); ?></td><td align="center"><?php echo ($val['item_nums']); ?></td><td align="center"><?php echo ($val['item_likes']); ?></td><td align="center" class="red"><?php if($val['collect_time'] != '0'): echo (date('Y-m-d H:i:s',$val["collect_time"])); endif; ?></td><td align="center"><a href="javascript:collect(<?php echo ($val['id']); ?>, '<?php echo ($val['name']); ?>');" class="blue">采集</a></td></tr><?php endforeach; endif; else: echo "" ;endif; endif; ?></tbody></table></form></div><script language="javascript">$(function(){   	
	$('.expandable').click(function(){ 
		var subcate=".sub_"+$(this).attr('id');
		if($(subcate+":visible").size()>0){ 
			$(subcate).hide();
			$(this).attr('src',ROOT_PATH+'/statics/admin/images/tv-expandable.gif');
		}else{
			$(subcate).show();
			$(this).attr('src',ROOT_PATH+'/statics/admin/images/tv-collapsable.gif');
		}
	});
});
function collect(cate_id, cate_name) {
	window.top.art.dialog({id:'collect'}).close();
	window.top.art.dialog({title:'淘宝数据采集--'+cate_name,id:'collect',iframe:'?m=items_collect&a=taobao_collect&cate_id='+cate_id+'&cate_name='+cate_name,width:'430',height:'160'});
}
function get_child_cates(obj)
{
	var parent_id = $(obj).val();
	if( parent_id ){
		$.get('?m=items_collect&a=get_child_cates&parent_id='+parent_id,function(data){
				var obj = eval("("+data+")");
				$('#items_cate_list').html( obj.content );
	    });
    }
}
</script><?php else: endif; ?></div></body></html>