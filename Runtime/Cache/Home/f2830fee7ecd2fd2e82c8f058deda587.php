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
    	<p class="page_index_xttz">系统消息：2020/02/02 12:02:02</p>
    	<p class="page_index_tznr">
           <!-- <b>会员每天可接取任务</b><br>
            观众   2个任务 1元/个<br>
            网红 15个任务 2元/个<br>
            明星 20个任务 3元/个<br>
            至尊 30个任务 3元/个<br><br>
            下级会员升级奖励<br>
            观众 1级/2元   2级/1元 <br>
            网红 1级/20元 2级/10元 <br>
            明星 1级/40元 2级/20元 <br>
            至尊 1级/60元 2级/40元-->
            <br>
1，超热点平台是一个什么样的平台?
<br><br>

超热点平台是一家助力自媒体发展的平台，实现用较低的成本，帮助各大电商企业产品在微信引流，助力推广。同时目前各种转发，体验，下载注册任务需求也十分庞大。超热点平台是一个多方共同合作，互利共赢的平台。
<br>
<br>
2，如何在平台发布自己的任务?
 <br>    <br>
超热点目前与多家优质企业，公司，自媒体工作室，大型商户合作，由于合作数量较多，目前任务档期排满，暂未开放个人合作通道，具体后期开放，等待平台通知即可投放任务！
<br>
<br>
3，每天可以领取多少任务?
   <br><br>
目前处于试运营期间，单个会员每天可领取5至20个任务，等待平台彻底完善，将上线更多的优质任务，并且将增加用户任务可领取数量。 
<br>
<br>
4，如何邀请好友，赚取更多佣金？
<br><br>
点击底部菜单栏海报，获取专属邀请二维码。 邀请好友可以获得一级好友*%佣金提成，二级好友*%佣金提成，三级好友*%佣金提成，邀请的用户完成任务，即可以获得奖励，邀请越多，奖励越丰富。
<br>
<br>
5，平台任务审核需要多长时间?
 <br><br>
由于平台任务数量庞大，采用的是智能识图自动审核系统，审核时间为5-30分钟左右。
<br>
<br>
6，佣金如何提现，多久可以到账?
<br>  <br>
超热点平台与支付宝服务商合作对接，仅支持支付宝提现，满*元即提，倍数提，秒到账。特殊情况2小时之内。

请各位用户在每天做任务的时候，全部按照要求来做，提现之前，认真核对自己的支付宝账号跟姓名是否正确无误。
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