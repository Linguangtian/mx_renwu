<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
    	<p class="page_index_xttz"></p>
    	<p class="page_index_tznr">
          1，小星*会员价格26元。<br>
每日任务收益2元-4元。<br>
一代推荐奖3元，二代推荐奖1元，三代推荐奖0.5元。<br>
一代收益提成30%，二代收益提成20%，三代收益提成10%。<br>
直推20人，拿团队见点1元/人。<br>
<br>
2，推手*会员价格89元。<br>
每日任务收益7元-10元。<br>
一代推荐奖12元，二代推荐奖4元，三代推荐奖2元。<br>
一代收益提成30%，二代收益提成20%，三代收益提成10%。<br>
直推15人，拿团队见点3元/人。<br>
<br>

3，宝主*会员价格280元。<br>
每日任务收益22元-30元。<br>
一代推荐奖38元，二代推荐奖16元，三代推荐奖8元。<br>
一代收益提成30%，二代收益提成20%，三代收益提成10%。<br>
直推10人，拿团队见点10元/人。<br>
<br>
佣金如何提现，多久可以到账?<br>
超热点平台与支付宝服务商合作对接，仅支持支付宝提现，满20元即提，倍数提，秒到账。特殊情况2小时之内到账，一天一次，提现时间上午10点到下午5点。<br>
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