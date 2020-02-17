<?php
/*
fastpay版本2.13
*/
@error_reporting(E_ALL^E_NOTICE);

define("PAY_VERSION", '3');//版本
define("PAY_VERSION_P", '2.13');//版本
//FAST客户支付配置
define("FAST_APPKEY", "21337_dee15a4609b1d0d00b9d20b7e4ba34dd");//你的appkey
define("SECRET_KEY", "3009c5599e35993883a49925e923fdf9");//你的秘钥
define('PAY_DESC', "xx平台给你汇款");//汇款备注
define('PAY_RETURN_URL', "http://".$_SERVER['HTTP_HOST']);//支付后成功后返回的页面,
define('PAY_NOTIFY_URL', "");//异步回调页面,不填写默认就是会员中心的---优先顺序-下单时候传入优先--->这里的回调----->会员中心的
define('PAY_OPENID', 1);//是否开启代汇款接口,0,关闭,1,为开启,
define('PAY_WAY', 'all');//all为支付宝跟微信双通道,wechat为微信支付,alipay为支付宝通道






/*
*****************************************
上面的信息可以自行配置,下面的不懂请勿修改!!!!!!!
以下参数无需修改,以下信息不懂请勿修改
*****************************************
*/



//FAST的SDK配置，
define("PAY_API", 'http://api.hxs823.cn/home/Public/pay/api.php');//请求的api
/**----汇款接口---------**/
define("API_TRANSFERS", PAY_API."?pay_type=transfers&v=".PAY_VERSION);

//获取下单地址
function fastpay_order($paydata, $type="http")
{
    if (!is_array($paydata)) {
        exit("data错误");
    }
    $data = array(
    'appkey'=>FAST_APPKEY,//你的appkey
    'pay_way'=>'wechat',//wechat为微信支付,alipay为支付宝
    'uid'=>'',//你的用户id
    'total_fee'=>'',//你的金额
    'order_no'=>'',//你的订单号
    'pay_title'=>'微信支付',//你的订单号
    'me_param'=>'',//其他参数,可返回回调里面
    'notify_url'=>PAY_NOTIFY_URL,//异步回调地址
    'me_back_url'=>PAY_RETURN_URL,//支付成功后返回
    'me_eshop_openid'=>'',//付款用户openid
    'me_party'=>'',//根据其他支付插件,异步回调返回同样参数,比如填写codepay,码支付,我们异步回调的时候就按码支付的回调参数返回
    'sign'=>''//签名
    );

    $data=array_merge($data, $paydata);
    extract($data);
    if (empty($appkey)) {
        exit("appkey没有填写");
    }
    if (empty($total_fee)) {
        exit("金额不能为空");
    }
    if (empty($uid)) {
        exit("付款用户id不能为空");
    }
    if (empty($order_no)) {
        $data['order_no']=md5(time() . mt_rand(1, 1000000));
    }
    if (!empty($me_back_url)) {
        $data['me_back_url']=urlencode($me_back_url);//
    }
    if (!empty($notify_url)) {
        $data['notify_url']=urlencode($notify_url);//
    }
    try {
        $data['total_fee']=bcadd($total_fee, 0, 2);
    } catch (Exception $e) {
        print_r($e->getMessage());
        exit("php不支持bcadd");
    }
    if (empty($sign)) {
        $data['sign']=pay_sign($data);
    }
    $url_quer=http_build_query($data);
    //$url=($data['pay_way']=='wechat') ? "/fastpay/fpay/wechat.php?{$url_quer}" : "/fastpay/fpay/alipay.php?{$url_quer}" ;//微信支付还是支付宝支付

    //pay_way为常量,在上面定义了
    switch ($paydata['pay_way']) {
       case "wechat":
        $url="/fastpay/fpay/wechat.php?{$url_quer}";//微信支付通道
         break;
       case "alipay":
       $url="/fastpay/fpay/alipay.php?{$url_quer}";//支付宝支付通道
        break;
       case "all":
        $url="/fastpay/fpay/choose.php?{$url_quer}";//支付宝跟微信双通道
         break;
       default:
        $url="/fastpay/fpay/choose.php?{$url_quer}";//支付宝跟微信双通道
    }
    return $url;
}




//获取用户openid，$back_url为获取pay_openid后返回的地址
function get_openid($back_url='')
{
    if (PAY_OPENID!=1) {return '';}

    //如果本地有保存,则返回无需请求
    if (!empty($_COOKIE['pay_openid'])) {
        return $_COOKIE['pay_openid'];
    }

    //curl获取请求连接
    $data=array();
    $data['appkey']=FAST_APPKEY;
    $data['type']='openid_url';
    $str_sign="appkey=".FAST_APPKEY."&secretkey=".SECRET_KEY."&";
    $sign=md5($str_sign);
    $data['sign']=$sign;//支付签名
    $res=get_cur(PAY_API."?pay_type=openid_url", $data, "POST");
    if(!is_array($res)){
    $res=json_decode($res,true);
    }
    if($res['status']=='n'){
      exit($res['info']);
    }

    $openid_url="http://".$res['url']."/code.php?v=".PAY_VERSION."&ver=".PAY_VERSION_P;
    if (!isset($_GET['pay_openid'])) {
        //获取openid后返回的页面
        $scope="snsapi_base";
        if(empty($back_url)){
          $back_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        }
        $back_url=urlencode($back_url);//url编码
        $login_api=$openid_url."&back_url={$back_url}&scope={$scope}";
        exit("<meta http-equiv='Refresh' content='0;URL={$login_api}'>");
    } else {
        $time_out=time()+3600*3;//一天后过期
        $_COOKIE['pay_openid']=$_GET['pay_openid'];
        setcookie("pay_openid", $_GET['pay_openid'], $time_out, "/");
        return $_GET['pay_openid'];
    }
}




//获取用户信息
function get_openid_info($arr)
{
    if (!is_array($arr)) {
        exit("data错误");
    }
//如果本地有则直接返回
    if (!empty($_COOKIE['pay_user_info'])) {
        $res=$_COOKIE['pay_user_info'];
        if (is_array($res)) {
            $res=json_encode($res);
        }
        $res=stripslashes($res);
        $res=str_replace("'", "", $res);
        return $res;
    }
    //curl获取请求连接
    $data=array();
    $data['appkey']=FAST_APPKEY;
    $data['type']='userinfo_url';
    $str_sign="appkey=".FAST_APPKEY."&secretkey=".SECRET_KEY."&";
    $sign=md5($str_sign);
    $data['sign']=$sign;//支付签名
    $res=get_cur(PAY_API."?pay_type=userinfo_url", $data, "POST");
    if(!is_array($res)){
    $res=json_decode($res,true);
    }
    if($res['status']=='n'){
      exit($res['info']);
    }
    $userinfo_url="http://".$res['url']."/code_user.php?v=".PAY_VERSION."&ver=".PAY_VERSION_P;

    if (isset($arr['fast_weixin_token'])) {
        $data=array();
        $data['fast_weixin_token']=$arr['fast_weixin_token'];
        $res=get_cur($userinfo_url, $data, "POST");
        $time_out=time()+3600*3;//一天后过期
        $_COOKIE['pay_user_info']=$res;
        setcookie("pay_user_info", $res, $time_out, "/");
        setcookie("fast_weixin_token", $arr['fast_weixin_token'], $time_out, "/");//以后可以通过这个获取用户信息,可以保存数据库
        $res=stripslashes($res);
        $res=str_replace("'", "", $res);
        return $res;
    }


    if (empty($arr['back_url'])) {
        $arr['back_url']='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    $sign=md5(FAST_APPKEY.SECRET_KEY.$arr['back_url']);
    $options['appkey']=FAST_APPKEY;
    $options['sign']=$sign;
    $options['back_url']=$arr['back_url'];
    $options['scope']="snsapi_userinfo";
    $url=http_build_query($options);
    $login_api=$userinfo_url."&{$url}";
    exit("<meta http-equiv='Refresh' content='0;URL={$login_api}'>");
}



//微信汇款接口
function fast_pay($paydata)
{
    if (!is_array($paydata)) {
        exit("data错误");
    }

    if (!isset($paydata['sh'])) {
        $paydata['sh']=0;
    }

    if (empty($paydata['appkey'])) {
        $paydata['appkey']=FAST_APPKEY;
    }
    $data=array();
    $data['appkey']=(string)$paydata['appkey'];
    $data['openid']=(string)$paydata['openid'];
    $data['amount']=(string)$paydata['amount'];
    $data['billno']=(string)$paydata['billno'];
    $data['desc']=(string)$paydata['desc'];
    $data['uid']=$paydata['uid'];
    $data['pay_way']='wepay';
    $data['sh']=$paydata['sh'];
    $str_sign="appkey={$data['appkey']}&openid={$data['openid']}&amount={$data['amount']}&billno={$data['billno']}&secretkey=".SECRET_KEY."&";
    $sign=md5($str_sign);
    $data['sign']=$sign;//支付签名
    $res=get_cur(API_TRANSFERS, $data, "POST");
    return $res;
}





//支付下单计算签名
function pay_sign($paydata)
{
    if (!is_array($paydata)) {
        exit("data错误");
    }
    $str_sign="appkey={$paydata['appkey']}&order_no={$paydata['order_no']}&secretkey=".SECRET_KEY."&total_fee={$paydata['total_fee']}&uid={$paydata['uid']}&";
    $sign=md5($str_sign);
    return $sign;
}


//异步回调计算签名
function notify_sign($paydata)
{
    if (!is_array($paydata)) {
        exit("data错误");
    }
    $str_sign="appkey={$paydata['appkey']}&order_no={$paydata['order_no']}&secretkey=".SECRET_KEY."&me_pri={$paydata['me_pri']}&uid={$paydata['uid']}&";
    $sign=md5($str_sign);
    return $sign;
}



//模拟http
function get_cur($url, $data="", $type="GET", $header="")
{
    $HTTP_REFERER='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_REFERER, $HTTP_REFERER);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    if (!empty($header)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $temp = curl_exec($ch);
    curl_close($ch);
    return $temp;
}


//判断是否是微信端
function is_weixin_pay()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }
    return false;
}


//日志记录
function fastpay_logger($log_content)
{
    $log_filename = "log.xml";
    if (is_array($log_content)) {
        $log_content = print_r($log_content, true);
    }
    file_put_contents($log_filename, date('H:i:s') . " " . $log_content . "\r\n", FILE_APPEND);
}
