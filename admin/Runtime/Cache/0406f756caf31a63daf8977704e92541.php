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

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><script type="text/javascript" src="__ROOT__/includes/kindeditor/kindeditor-min.js"></script><script type="text/javascript">	//编辑器
	KE.show({
		id : 'info',
		imageUploadJson : '__ROOT__/includes/kindeditor/php/upload_json.php',
		fileManagerJson : '__ROOT__/includes/kindeditor/php/file_manager_json.php',
		allowFileManager : true,
		afterCreate : function(id) {
			KE.event.ctrl(document, 13, function() {
				KE.util.setData(id);
				document.forms['myform'].submit();
			});
			KE.event.ctrl(KE.g[id].iframeDoc, 13, function() {
				KE.util.setData(id);
				document.forms['myform'].submit();
			});
		}
	});
	$(function(){
		$("#add_pic").click(function(){
			$("#pic_tr").clone().prependTo($("#pic_tr").parent());
		});
		
		$(".delete_pic").click(function(){
			var item_id = $("#items_id").val();
			var id = $(this).attr('id');
			$.get("admin.php?m=items&a=delete_pic&item_id="+item_id+"&id="+id, function(data){
				$("#list_"+id).hide();
			});
		});
		
	})
</script><form action="<?php echo u('items/edit');?>" method="post" name="myform" id="myform" enctype="multipart/form-data" style="margin-top:10px;"><div class="pad-10"><div class="col-tab"><ul class="tabBut cu-li"><li id="tab_setting_1" class="on" onclick="SwapTab('setting','on','',3,1);">基本信息</li><li id="tab_setting_2" onclick="SwapTab('setting','on','',3,2);">商品详情</li><li id="tab_setting_3" onclick="SwapTab('setting','on','',3,3);">SEO设置</li></ul><div id="div_setting_1" class="contentList pad-10"><table width="100%" cellpadding="2" cellspacing="1" class="table_form"><tr><th width="100">商品名称 :</th><td><input type="text" name="title" id="title" class="input-text" size="60" value="<?php echo ($items["title"]); ?>"></td></tr><tr><th>所属分类 :</th><td><select onchange="get_child_cates(this,'scid');" name="pcid" id="pcid"><option value="">--请选择--</option><?php if(is_array($first_cates_list)): $i = 0; $__LIST__ = $first_cates_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($first_id == $val['id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select>	          &nbsp;-&nbsp;
	          <select onchange="get_child_cates(this,'cid');" name="scid" id="scid"><option value="">--请选择--</option><?php if(is_array($second_cates_list)): $i = 0; $__LIST__ = $second_cates_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($second_id == $val['id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select>	          &nbsp;-&nbsp;
	          <select name="cid" id="cid"><option value="">--请选择--</option><?php if(is_array($three_cates_list)): $i = 0; $__LIST__ = $three_cates_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if($three_id == $val['id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></td></tr><tr><th>封面图片</th><td><img src="<?php echo ($items["img"]); ?>" style="border: 1px solid #999999;padding:1px;"/><br /><br /><input type="file" name="img" id="img" class="input-text" size=21 /></td></tr><tr><th>来源 :</th><td><select name="sid" id="sid" disabled><option value="0" selected="selected">--选择来源--</option><?php if(is_array($site_list)): $i = 0; $__LIST__ = $site_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" alias="<?php echo ($val["alias"]); ?>" <?php if($items['sid'] == $val['id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></td></tr><tr><th>链接地址 :</th><td><input type="text" name="url" id="url" class="input-text" value="<?php echo ($items["url"]); ?>" size="60"></td></tr><tr><th>标签 :</th><td><input type="text" name="tags" id="tags" class="input-text" value="<?php echo ($items["tags"]); ?>" size="60"></td></tr><tr><th>价格 :</th><td><input type="text" name="price" id="price" class="input-text" value="<?php echo ($items["price"]); ?>" size="10">元</td></tr><tr><th>喜欢数 :</th><td><input type="text" name="likes" id="likes" class="input-text" value="<?php echo ($items["likes"]); ?>" size="10" onkeyup="value=value.replace(/[^\d]/g,'') "onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"></td></tr><tr><th>浏览数 :</th><td><input type="text" name="hits" id="hits" class="input-text" value="<?php echo ($items["hits"]); ?>" size="10" onkeyup="value=value.replace(/[^\d]/g,'') "onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"></td></tr><?php if($items["uid"] > 0): ?><tr><th>分享介绍 :</th><td valign="middle"><textarea style="width:300px;height:100px;" class="input-text" name="remark"><?php echo ($items["remark"]); ?></textarea>	        	不显示<input type="radio" value="0" name="remark_status" <?php if($items["remark_status"] == 0): ?>checked="checked"<?php endif; ?>/>&nbsp;&nbsp;
	            显示<input type="radio" value="1" name="remark_status" <?php if($items["remark_status"] == 1): ?>checked="checked"<?php endif; ?>/></td></tr><?php endif; ?><tr><th>发布时间 :</th><td><input type="text" class="input-text" value="<?php echo (date("Y-m-d H:i:s",$items["add_time"])); ?>" size="30"></td></tr><tr><th>更新时间 :</th><td><input type="text" class="input-text" value="<?php echo (date("Y-m-d H:i:s",$items["last_time"])); ?>" size="30"></td></tr></table></div><input type="hidden" name="id" value="<?php echo ($items["id"]); ?>" id="items_id"/><div id="div_setting_2" class="contentList pad-10 hidden"><table width="100%" cellpadding="2" cellspacing="1" class="table_form"><tr id="pic_tr"><th width="100">商品配图 :</th><td><input type="file" name="pic[]" class="input-text" size=21 /></td></tr><tr><th></th><td><input type="button" value="增加图片" id="add_pic" class="button"></td></tr><tr><th>已上传配图 :</th><td><?php if(is_array($pic_list)): $i = 0; $__LIST__ = $pic_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div id="list_<?php echo ($val["id"]); ?>"><img width=120 height=120 src="<?php echo ($val["url"]); ?>" />&nbsp;&nbsp;<a href="javascript:;" class="delete_pic" style="color:#00F" id="<?php echo ($val["id"]); ?>">删除</a><br/></div><?php endforeach; endif; else: echo "" ;endif; ?></td></tr><tr><th>详细内容 :</th><td><textarea name="info" id="info" style="width:80%;height:250px;visibility:hidden;"><?php echo ($items["info"]); ?></textarea></td></tr></table></div><div id="div_setting_3" class="contentList pad-10 hidden"><table width="100%" cellpadding="2" cellspacing="1" class="table_form"><tr><th width="100"><?php echo (L("seo_title")); ?> :</th><td><input type="text" name="seo_title" id="seo_title" class="input-text" size="60" value="<?php echo ($items["seo_title"]); ?>"></td></tr><tr><th><?php echo (L("seo_keys")); ?> :</th><td><input type="text" name="seo_keys" id="seo_keys" class="input-text" size="60" value="<?php echo ($items["seo_keys"]); ?>"></td></tr><tr><th><?php echo (L("seo_desc")); ?> :</th><td><textarea name="seo_desc" id="seo_desc" cols="80" rows="8"><?php echo ($items["seo_desc"]); ?></textarea></td></tr></table></div><div class="bk15"></div><div class="btn"><input type="submit" value="<?php echo (L("submit")); ?>" name="dosubmit" class="button" id="dosubmit"></div></div></div></form><script type="text/javascript">function SwapTab(name,cls_show,cls_hide,cnt,cur){
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
function get_child_cates(obj,to_id) {
	var parent_id = $(obj).val();
	var pcid = $('#pcid').val();
	if(to_id == 'scid') {
		$('#cid').html( '<option value=\"\">--请选择--</option>');
	}
	$.get('?m=items&a=get_child_cates&g=admin&parent_id='+parent_id,function(data){
		var obj = eval("("+data+")");
		$('#'+to_id).html( obj.content );
	});
}
</script></body></html>