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

</script><title><?php echo (L("website_manage")); ?></title></head><body><div id="ajax_loading">提交请求中，请稍候...</div><?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content-menu ib-a blue line-x"><?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?></div></div><?php endif; endif; ?><style type="text/css">td,th{ 
	height:30px;
}
</style><form action="<?php echo u('items/collect_by_words');?>" method="post" name="myform" id="myform"><div class="pad-10"><div class="col-tab"><ul class="tabBut cu-li"><li id="tab_setting_1" class="on" onclick="SwapTab('setting','on','',2,1);">基本信息</li></ul><div id="div_setting_1" class="contentList pad-10"><table><tr><th width="120">商品归类 :</th><td><select name="cate_id" id="cid" onchange="return check_cate();"><option value="0">--请选择分类--</option><?php if(is_array($cate_list)): $i = 0; $__LIST__ = $cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" level="<?php echo ($val["level"]); ?>"><?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$val['level']); echo trim($val['name']);?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></td></tr><tr><th>关键词：</th><td><input type="text" id="keywords" name="keywords" class="input-text" value=""/></td></tr><tr><th>采集页数：</th><td><input type="text" id="page" name="pages" class="input-text" value="10"/></td></tr><tr><th>采集消息：</th><td id="cmd"></td></tr></table></div><div class="btn"><input type="submit" name="dosubmit" value="开始采集" class="button" id="dosubmit"/><input type="button" value="刷新页面" class="button" id="reload" style="display:none;"/></div></div><br><table width="100%" cellspacing="0" class="search-form"><tbody><tr><td><div class="explain-col"><font color=red><b>备注说明：</b><br>	               　　	1、该采集是采集淘宝开放平台的商品信息;<br>	               　　 2、"关键词"是通过商品的标题名称进行匹配;<br>	               　　 3、采集页数建议在2~10页间;<br></font></div></td></tr></tbody></table></div></form><script type="text/javascript">var cate_id="<?php echo ($data["cate_id"]); ?>";
var keywords="<?php echo ($data["keywords"]); ?>";
var pages="<?php echo ($data["pages"]); ?>";
var p=parseInt("<?php echo ($p); ?>")||0;
var pages=parseInt("<?php echo ($data["pages"]); ?>")||0;
//console.log(p>0);
$('#cid').val(cate_id);
$('#keywords').val(keywords);
if(pages>0){ 
	$('#page').val(pages);
}
//$("#cmd").html("<div style='color:green;'>采集完成,共采集"+p+"页</div>");
if(p>0){ 
	if(p>=pages){ 
		$("#cmd").html("<div style='color:green;'>采集完成,共采集"+p+"页</div>");
		$('#dosubmit').remove();
		$('#reload').show().click(function(){ 
			window.location.href="admin.php?m=items&a=collect_by_words";											
		});
	}else{
		$("#cmd").html("<div class='loading'>"+p+"页已采集完成!</div>");	
		window.location.href="admin.php?m=items&a=collect_by_words&cate_id="+cate_id+"&keywords="+keywords+"&pages="+pages+"&p="+(p+1)+"&dosubmit=1";
	}
}
$(function(){ 
	$('#cid').change(function(){ 						  
		//console.log($('option[selected=true]',$(this)));
		check_cate(this);
		
		$('#keywords').val($.trim($('option[selected=true][value!="0"]',$(this)).text()));
	});		   
});
</script></body></html>