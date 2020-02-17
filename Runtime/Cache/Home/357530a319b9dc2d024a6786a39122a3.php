<?php if (!defined('THINK_PATH')) exit(); $title = $show['title'];?>
<!DOCTYPE html>
<html>
<head>
    <?php
 $version = time(); ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <title><?php echo ($title); ?></title>
    <link rel="stylesheet" href="/tpl/Public/css/share.css?<?php echo ($version); ?>">
    <link rel="stylesheet" href="/tpl/Public/css/font.css?<?php echo ($version); ?>" />
    <!--JQ-->
    <script type="text/javascript" src="/tpl/Public/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/tpl/Public/js/jquery-form.js"></script>
    <script type="text/javascript" src="/tpl/Public/js/layer_mobile2/layer.js?<?php echo ($version); ?>"></script>
    <script type="text/javascript" src="/tpl/Public/js/swiper.3.1.7.min.js"></script>

    <script src="/tpl/Public/js/jquery.simplesidebar.js"></script>
    <script src="/tpl/Public/js/jquery.SuperSlide.2.1.1.js"></script>
    <script src="/tpl/Public/js/TouchSlide.1.0.js"></script>

    <script type="text/javascript" src="/tpl/Public/js/func.js?<?php echo ($version); ?>"></script>
    <script>
        var SITE_URL  = 'https:' == document.location.protocol ?'https://' : 'http://' + "<?php echo $_SERVER['HTTP_HOST'];?>";
    </script>
</head>
<script type="text/javascript" src="/tpl/Public/js/clipboard.min.js"></script>
<style>
	.renwu_act{
		height:3rem; display:flex;width: 100% ;padding-top: 1rem;
	}
	.renwu_act>div{
		width: 50%;text-align: center;
	}
	.renwu_act a{
		padding: 0.3rem 0.4rem;
		border-radius: 0.5rem;
		color: #ffffff;
	}
	.renwu_act .al{
		background: #b02fd3;
	}
	.renwu_act .a2{
		background: red;
	}
</style>
<body class="show_bg">
	
	
	<div class="show_top_o" style=" height: 16rem;">
		
		<!--logo-->
		
		<div class="show_top_logo">
			<img src="/tpl/Public/images/eee.png"/>
			<div class="index_btswz" style="margin-left: 0;width: 50%;">
					<p class="index_bta"><?php echo ($show["title"]); ?></p>
					<p class="index_tag"> <span>优质任务</span>  </p>
				</div>
				<p class="show_yj">+<?php echo (floatval($show["price"])); ?>元</p>
		</div>
		
		<p class="show_rwlq">亲，请严格按照任务要求做任务哟！</p>
		
		<!--logo结束-->
		
		<a class="show_lqjl" href="<?php echo ($show["info"]); ?>">打开链接/复制链接 <span>>完成操作</span>  <span>>上传截图等待审核</span>   </a>
		<div class="clear"></div>
		<div class="renwu_act" >
			<div class="act_li"  ><a class="al" href="<?php echo ($show["tolink"]); ?>" target="_blank" >打开视频</a></div>
			<div   class="act_li" ><a href="javascript:void(0)"  data-clipboard-action="copy" data-clipboard-target=".lianjie" id="copy3" class="a2">复制链接</a></div>
		</div>

	</div>
	<div class="lianjie" style="display: none">
		<?php echo ($show["tolink"]); ?>
	</div>
	<div class="show_rwxq">
        <?php echo ($show["content"]); ?>
	</div>
	<div style="clear:both;height:10px;width:100%"></div>
<!--	<a href="javascript:void(0)" id="copy2" data-clipboard-action="copy" data-clipboard-target=".show_rwxq" style="display:block;width:130px;height:40px;border:1px solid #EEAD0E;clear:both;text-align:center;line-height:40px;border-radius:10px;color:#EEAD0E;margin:10px auto;font-size:16px; ">一键复制文案</a>
	
	-->
	
	
	
	<div class="show_rwxf">
        <?php if($btn_status == 0): ?><button type="button" class="bala-btna" disabled><?php echo ($status_text); ?></button><?php endif; ?>
        <?php if($btn_status == 1): ?><button type="button" class="bala-btn get_task" style="background: #b02fd3;border: none;float: left;"><?php echo ($status_text); ?></button><?php endif; ?>
        <?php if($btn_status == 2): ?><a href="<?php echo U('submission_task_do',array('id'=>$task_apply['id']));?>" class="bala-btn" style="background: #b02fd3;border: none;float: left;"><?php echo ($status_text); ?></a><?php endif; ?>
    </div>

<!-- 底部联系部分 开始 -->

</body>
</html>

<script>
    //复制文案
    var clipboard = new ClipboardJS('#copy2');
    clipboard.on('success', function(e) {
        sp_alert('复制成功');
    });
  var clipboard = new ClipboardJS('#copy3');
    clipboard.on('success', function(e) {
        sp_alert('复制成功');
    });

    clipboard.on('error', function(e) {
        sp_alert('复制信息出错，请手动复制');
    });

    //领取任务
    $('.get_task').click(function(){
        var task_id = "<?php echo ($show["id"]); ?>";
        var url = "<?php echo U('get_task');?>";
        $.post(url,{id:task_id},function(data){
            if( data.status == 1 ) {
                sp_alert(data.info);
                $('.bala-btn').css('background-color','#ccc').html('任务已领取');
            } else {
                sp_alert(data.info);
            }
        },'json')
    })
</script>