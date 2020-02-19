<?php if (!defined('THINK_PATH')) exit();?><title>平台奖励</title>
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

<body class="gray_bg">
<div class="body_main">
    <!-- 头部部分 开始 -->
    <div class="page_index_tz" style="height: auto;padding-bottom: 20px;">
    	<p class="page_index_xttz">平台奖励</p>
    	<p class="page_index_tznr">
			黄金<br>
一级推荐奖励20%<br>
一级任务奖励2%<br>
			<br>

			钻石<br>
			一级推荐奖励25%<br>
			二级推荐奖励10%<br>
			一级任务奖励3%<br>
			二级任务奖励2%<br>
			<br>
			<br>
			钻石<br>
			一级推荐奖励25%<br>
			二级推荐奖励10%<br>
			一级任务奖励3%<br>
			二级任务奖励2%<br><br>
			王者<br>
			一级推荐奖励40%<br>
			二级推荐奖励15%<br>
			三级推荐奖励10%<br>
			一级任务奖励6%<br>
			二级任务奖励4%<br>
			三级任务奖励2%<br>
<br>
佣金如何提现，多久可以到账?<br>
超热点平台与支付宝服务商合作对接，仅支持支付宝提现，倍数提，秒到账。特殊情况2小时之内到账，一天一次，提现时间上午10点到下午5点。<br>
        </p>
    	<!--<a class="page_index_ckxxnr" href="#">查看详细内容</a>-->
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
 
     
     <footer class="footer">
     	<ul>
     		<a href="<?php echo U('Index/index');?>">
     			<li>
     				<img src="/tpl/Public/images/home_a.png" />
     				<p style="color: #b5b5b5;font-size: 12px;">首页</p>
     			</li>
     		</a>
     
     		<a href="<?php echo U('Task/index');?>">
     			<li>
     				<img src="/tpl/Public/images/task_a.png" />
     				<p style="color: #b5b5b5;font-size: 12px;">大厅</p>
     			</li>
     		</a>
     
     		<a href="<?php echo U('Member/vip');?>">
     			<li class="task_shop">
     				<img class="foot_shop" src="/tpl/Public/images/shop_a.png?v=1" />
     			</li>
     		</a>
     
     		<a href="<?php echo U('Page/index');?>">
     			<li>
     				<img src="/tpl/Public/images/page_b.png?v=1" />
     				<p style="color: #f03851;font-size: 12px;">消息</p>
     			</li>
     		</a>
     
     		<a href="<?php echo U('Member/index');?>">
     			<li>
     				<img src="/tpl/Public/images/user_a.png" />
     				<p style="color: #b5b5b5;font-size: 12px;">我的</p>
     			</li>
     		</a>
     
     	</ul>
     </footer>
     
</div>
</body>
</html>