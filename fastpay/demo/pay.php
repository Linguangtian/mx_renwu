<?php

/*{"title":"收款4.00元（新客到店）","description":"收款金额¥4.00
订单金额¥4.00
顾客昵称新***
交易单号4200000460201912159228411632
收款门店叶子便利店的店铺20025
收款汇总该店今日第17笔收款，共¥95.81","app":"微信收款商业版","url":"https://payapp.weixin.qq.com/qrpay/order/home2?key=idc_CHNDVI_gGyxbTNZIWONAHIMkxD_HA--","jiqiid":""}*/




header('Content-Type: text/html; charset=utf-8');


if($app=="微信收款助手"  ||  $app=='微信收款商业版'){



}
//加载fastpay支付插件
if (!function_exists('get_openid')) {
require $_SERVER['DOCUMENT_ROOT'].'/fastpay/Fast_Cofig.php';
}
$order_no = $_GET['order_no'];
$money =$_GET['money'];
$payment_type = $_GET['payment_type'];
//$conn = new mysqli('127.0.0.1', 'sql_1lc_blxld_co', 'hb7DWGGPhtPPGHMJ');
//准备SQL语句

	/*while($row = $query->fetch_array()){
	    echo $row['id'],'<br>';
	}*/


//$res = M("recharge")->where("order_no = {$order_no}")->find();
//var_dump($res);die;
$paydata=array();
$paydata['pay_way']=$payment_type;//"wechat";//wechat为微信支付,alipay为支付宝
$paydata['uid']=154;//支付用户id
$paydata['order_no']=$order_no;//订单号
$paydata['total_fee']=$money;//金额
$paydata['param']="";//其他参数
$paydata['me_back_url']="http://lc.blxld.com/";//支付成功后跳转
$paydata['notify_url']="http://lc.blxld.com/Api/Iweipay/notify";//支付成功后异步回调

$geturl=fastpay_order($paydata);//获取支付链接

exit("<meta http-equiv='Refresh' content='0;URL={$geturl}'>");
