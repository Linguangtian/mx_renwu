<?php if (!defined('THINK_PATH')) exit(); $title = "申请提现";?>
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
            <div style="text-align: center;padding-bottom: 20px;"><p style="font-size: 14px;color: #999;padding-bottom: 3px;">总金额（元）</p> <span style="font-size: 44px;color: #dd5c51"><?php echo ($data["price"]); ?></span> </div>

            <form id="form1" class="submit-ajax" action="<?php echo U('tixian');?>" method="post">

                <?php if(empty($data['bank_name']) || empty($data['bank_user']) || empty($data['bank_number']) ): ?><div class="card no">
                        <a href="<?php echo U('Member/info_edit',array('field'=>'bank_number','f'=>'tixian'));?>">
                            <h5>请选完善收款渠道</h5>
                        </a>
                    </div>

                <?php else: ?>
                    <div class="card">
                        <a href="<?php echo U('Member/info_edit',array('field'=>'bank_number','f'=>'tixian'));?>">
                            <h5>收款渠道：<?php echo ($data["bank_name"]); ?></h5>
                            <p>开户人:<?php echo ($data["bank_user"]); ?> &nbsp; 账号:<?php echo ($data["bank_number"]); ?> </p>
                        </a>
                    </div><?php endif; ?>

                <!--<div class="con">
                    <div class="t">提现金额</div>
                    <div class="p">
                        <input type="text" name="price" value="" placeholder="">
                    </div>
                </div>-->

                <div style="padding: 20px 20px;border-top: 6px #f2f2f2 solid;margin-bottom: 20px;">
                    <i class="alipay-icon" style="float: left;margin-right: 6px;"></i> <span style="float: left;font-size: 16px;"> 支付宝收款</span>
                </div>

                <div class="tx_prices">
                    <span data-price="<?php echo ($new_member_tixian_price); ?>">提现<i><?php echo ($new_member_tixian_price); ?>元</i><b>新人专享</b></span>
                    <span data-price="<?php echo ($min_tixian_price); ?>">提现<i><?php echo ($min_tixian_price); ?>元</i></span>
                    <span data-price="0">其他</span>
                </div>
                <div class="tx_other_price">
                    <p>请输入提现金额</p>
                    <input type="text" name="price" id="price" value="" placeholder="0.00">
                </div>
                <div class="b" style="margin:40px 20px">
                    <?php if($can_tixian == 1): ?><button class="btn_type btn_tx">确定</button>
                        <?php else: ?>
                        <button disabled class="btn_type btn_tx" style="background: #ccc;">您今天已提过现，每日限1次</button><?php endif; ?>
                    <p style="padding-top: 15px;color: #666;line-height: 20px;">1.满20元既可提现，每次收取5%手续费，每日提现次数限1次<br>
                        2.提现即时到账，提现审核时间:10:00-17:00</p>
                </div>

            </form>
        </div>
    </div>
</div>
</body>
</html>
<script>
    $('.tx_prices span').click(function(){
        $(this).addClass('active').siblings().removeClass('active');
        var price = $(this).attr('data-price');
        if( price>0 ) {
            $('#price').val(price);
            $('.tx_other_price').hide();
        } else {
            $('#price').val('');
            $('.tx_other_price').show();
        }
    })
</script>