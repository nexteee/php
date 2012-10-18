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

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><form action="<?php echo u('items/batch_add');?>" method="post" name="myform" id="myform"  enctype="multipart/form-data" style="margin-top:10px;"><div class="pad-10"><div class="col-tab"><ul class="tabBut cu-li"><li id="tab_setting_1" class="on" onclick="SwapTab('setting','on','',2,1);">基本信息</li></ul><div id="div_setting_1" class="contentList pad-10"><table width="100%" cellpadding="2" cellspacing="1" class="table_form"><tr><th>所属分类 :</th><td><select onchange="get_child_cates(this,'scid');" name="pcid" id="pcid"><option value="">--请选择--</option><?php if(is_array($first_cates_list)): $i = 0; $__LIST__ = $first_cates_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($first_id == $val['id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select>&nbsp;-&nbsp;
			          		<select onchange="get_child_cates(this,'cid');" name="scid" id="scid"><option value="">--请选择--</option><?php if(is_array($second_cates_list)): $i = 0; $__LIST__ = $second_cates_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($second_id == $val['id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select>&nbsp;-&nbsp;
			          		<select name="cid" id="cid"><option value="">--请选择--</option><?php if(is_array($three_cates_list)): $i = 0; $__LIST__ = $three_cates_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($three_id == $val['id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></td></tr><!--
                    <tr><th width="120">商品归类 :</th><td><select name="cid" id="cid" onchange="return check_cate(this);"><option value="0">--请选择分类--</option><?php if(is_array($cate_list)): $i = 0; $__LIST__ = $cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" level="<?php echo ($val["level"]); ?>"><?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$val['level']); echo trim($val['name']);?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></td></tr>                    --><tr><th>网址URL：</th><td><textarea name="urls" id="urls" cols="100" rows="10"></textarea></td></tr><tr><th>采集消息：</th><td id="cmd">&nbsp;</td></tr></table></div><div class="btn"><input type="button" value="开始采集" class="button" onclick="collect();"></div></div><br><table width="100%" cellspacing="0" class="search-form"><tbody><tr><td><div class="explain-col"><font color=red><b>备注说明：</b><br>	               	　　1、该采集接口只适用于"淘宝网-taobao.com"、"天猫商城-tmall.com"两个网站<br>	               	　　2、URL链接样要求一行一个商品链接，如下所示：<br>	               　　	　http://detail.tmall.com/item.htm?id=12903959631<br/>					　　　 http://detail.tmall.com/item.htm?id=12903959631<br/>					　　　 …………<br/>					　　　 http://detail.tmall.com/item.htm?id=12903959631<br/></font></div></td></tr></tbody></table></div></form><script type="text/javascript">function collect(){ 
	$('#cmd').show();
	
	if($('#pcid').val()==''){ 
		//console.log($('#cid').val());	
		alert('请选择分类');
		return;
	}	
	$.post("<?php echo u('items/batch_add');?>",{
		dosubmit:true,
		urls:$('#urls').val(),
		cid:$('#cid').val(),
		scid:$('#scid').val(),
		pcid:$('#pcid').val()
	},
	function(data){
		$('#cmd').html("");		
		if(data.data.code){
			alert(data.data.msg);
			return;
		}
		var html = "<div><h4>成功更新列表:</h4><div style='color:blue;'>"+data.data.success_update_list+"</div></div><br/>\
					<div><h4>成功添加列表:</h4><div style='color:green;'>"+data.data.success_insert_list+"</div></div>\
					<div><h4>失败列表:</h4><div style='color:red;'>"+data.data.fail_list+"</div></div>";
		$('#cmd').append(html);					  			
		//console.log(data);
	},
	'json');	
}
function get_child_cates(obj,to_id) {
	var parent_id = $(obj).val();
	if(to_id == 'scid') {
		$('#cid').html( '<option value=\"\">--请选择--</option>');
	}
	$.get('?m=items&a=get_child_cates&g=admin&parent_id='+parent_id,function(data){
		var obj = eval("("+data+")");
		$('#'+to_id).html( obj.content );
	});
}	
</script></body></html>