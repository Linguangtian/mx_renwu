<?php if (!defined('THINK_PATH')) exit(); $title = "余额充值";?>
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
<div id="body">
    <!-- 头部部分 开始 -->
    <!--主体部分 开始-->
    <div class="body_main mt" style="margin-top: 0;">
        <div class="tixian_box">
            <form id="form1" action="" method="post">
                <input type="hidden" name="price" id="price" value="50">
                <div style="padding: 20px 20px;margin-bottom: 20px;">
                     <span style="float: left;font-size: 16px;"> 请选择充值金额</span>
                </div>

                <div class="tx_prices">
                    <span data-price="50" class="active"><i>50元</i></span>
                    <span data-price="100"><i>100元</i></span>
                </div>

                <div class="recharge_box" style="border-top: 0; padding-bottom: 40px;margin: 0 30px 0 30px;">
                    <input type="hidden" name="payment_type" id="payment_type" value="">
                    <!--<p>选择支付方式：</p>-->
                    <label data-key="alipay">
                        <i class="alipay"></i> 支付宝支付 <span></span>
                    </label>
                    <label data-key="wechat">
                        <i class="wechat"></i> 微信支付 <span></span>
                    </label>
                </div>

                <div class="b" style="margin:40px 20px">
                    <button class="btn_type btn_tx">确定充值</button>
                </div>

            </form>
        </div>
    </div>
</div>
</body>
</html>
<script>
    $('.recharge_box label').click(function(){
        $('.recharge_box label span').removeClass('active');
        $(this).find('span').addClass('active');
        var payment_type = $(this).attr('data-key');
        $('#payment_type').val(payment_type);
    });

    $('.tx_prices span').click(function(){
        $(this).addClass('active').siblings().removeClass('active');
        var price = $(this).attr('data-price');
        $('#price').val(price);
    })
</script>