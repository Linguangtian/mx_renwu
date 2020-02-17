<?php
@error_reporting(E_ALL^E_NOTICE);
header('Content-Type: text/html; charset=utf-8');



extract($_GET);
extract($_POST);

//获取来路
$me_from=$_SERVER["HTTP_REFERER"];//来路
$me_from=urlencode($me_from);//来路
$back_url=urldecode($me_back_url);//解码支付成功后的页面
//判断是否是微信
$pay_openid=$_COOKIE['get_openid'];


if (empty($back_url)) {
    $back_url="/";
}

$query=$_SERVER["QUERY_STRING"];

$wechat_url="wechat.php?".$query;
$alipay_url="alipay.php?".$query;
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $pay_title?></title>
<meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
<meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<script src='js/jquery.min.js'></script>
<link rel="stylesheet" href="css/layui.css">
</head>
<body>

  <div class="layui-row" style="width:80%;margin:8px auto;">
    <a  href="<?php echo $wechat_url?>" class="layui-btn layui-btn-fluid layui-btn-danger">微信扫码支付</a>
  </div>

  <div class="layui-row" style="width:80%;margin:8px auto;">
    <a  href="<?php echo $alipay_url?>" class="layui-btn layui-btn-fluid layui-btn-normal">支付宝截图扫码支付</a>
  </div>

</body>
