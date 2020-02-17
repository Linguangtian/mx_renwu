<?php
namespace Api\Controller;

use Common\Model\PayModel;
use Think\Controller;

/**
 * 个人免签
 * http://i-weipay.com/doc.php#api6
 */
class IweipayController extends Controller{

    const UID = "695480";//"此处填写PaysApi的uid";
    const KEY = "824FE354E8B06948D38BC974DC721A01";//"此处填写PaysApi的Token";
    const HOST_URL = "http://pay.r3o.cn/submit.php?";

    public function pay(){
        $order_no = I('get.order_no');
        $type = I('get.payment_type')=='alipay' ? 'alipay' :'wxpay';
        $recharge = M('recharge')->where(array('order_no'=>$order_no))->find();

        $goodsname = "充值VIP";
        $notify_url = U('notify','','',true);
        $return_url = U('Paysapi/paysapi_return','','',true);
        $price = $recharge['price'];

        $parameter = array(
            "pid"            => self::UID,//平台ID号
            "type"      => $type,//支付方式 alipay	qqpay:QQ钱包,wxpay:微信支付
            "out_trade_no"  => $order_no,  //订单号
            "notify_url"    => $notify_url, // 付款成功通知地址
            "return_url"    => $return_url, // 页面跳转通知地址
            "name"         => $goodsname,//商品名称
            "money"        => $price,//自定义参数
            "sitename"      => "赞多多",
        );
        ksort($parameter); //重新排序$parameter数组
        reset($parameter); //内部指针指向数组中的第一个元素
        $sign = '';
        $urls = '';
        foreach ($parameter AS $key => $val) {
            if ($val == '') continue;
            if ($key != 'sign') {
                if ($sign != '') {
                    $sign .= "&";
                    $urls .= "&";
                }
                $sign .= "$key=$val";  //拼接为url参数形式
                $urls .= "$key=" . urlencode($val); //拼接为url参数形式
            }
        }
        $key = md5($sign . self::KEY);// MD5签名
        $query = $urls . '&sign=' . $key . "&sign_type=MD5"; // 创建订单所需的参数

        $url = self::HOST_URL . $query; //构造请求url


        /*$sign = md5( http_build_query($parameter) . self::KEY);
        $sign_type = "MD5";

        $query = http_build_query($parameter) . "&sign={$sign}&sign_type={$sign_type}";
        $url = self::HOST_URL . $query;
        */

        header("Location: $url");
    }

    /**
     * notify_url接收页面
     */
    public function notify(){
    	file_put_contents("11122.txt",print_r($_POST,true));
    	//die;
    	$_POST = $_POST;
    /*	$_POST = array(
			    'status' => 'y',
			    'info' => '支付成功',
			    'message_type' => 'weixin_pay',
			    'client_uid' => 'push74995eaae7c9059f65c2adc21e62',
			    'type' => 'sendToUid',
			    'appkey' => '21337_dee15a4609b1d0d00b9d20b7e4ba34dd',
			    'uid' => '154',
			    'total_fee' => '1.00',
			    'pay_title' => '微信支付',
			    'order_no' => 'C88888C26074478930595',
			    'client_id' => 'push74995eaae7c9059f65c2adc21e62',
			    'sign' => 'e51a06e55fce57a22fdc8b9f0aa322f7',
			    'pay_type' => 'gren_qr',
			    'pay_way' => 'wechat',
			    'me_back_url' => 'http%3A%2F%2Flc.blxld.com%2F',
			    'me_from' => 'http%3A%2F%2Flc.blxld.com%2Ffastpay%2Ffpay%2Fchoose.php%3Fappkey%3D21337_dee15a4609b1d0d00b9d20b7e4ba34dd%26pay_way%3Dwechat%26uid%3D154%26total_fee%3D1.00%26order_no%3DC88888C26074478930595%26pay_title%3D%25E5%25BE%25AE%25E4%25BF%25A1%25E6%2594%25AF%25E4%25BB%2598%26me_param%3D%26notify_url%3Dhttp%25253A%25252F%25252Flc.blxld.com%25252FApi%25252FIweipay%25252Fnotify%26me_back_url%3Dhttp%25253A%25252F%25252Flc.blxld.com%25252F%26me_eshop_openid%3D%26me_party%3D%26sign%3De51a06e55fce57a22fdc8b9f0aa322f7%26param%3D',
			    'notify_url' => 'http%3A%2F%2Flc.blxld.com%2FApi%2FIweipay%2Fnotify',
			   'me_pri' =>'1.00',
			   'sign_notify' => 'aa4330150b238e5bfa875dd3c4861ff9',
    		);*/
       $sign=$_POST['sign_notify'];//获取签名2.07以下请使用$sign=$_POST['sign'];
		$check_sign=$this->notify_sign($_POST);
		if($sign!=$check_sign){
		  exit("签名失效");
		//签名计算请查看怎么计算签名,或者下载我们的SDK查看
		}
		
		$uid         = $_POST['uid'];//支付用户
		$total_fee   = $_POST['total_fee'];//支付金额
		$pay_title   = $_POST['pay_title'];//标题
		$sign        = $_POST['sign'];//签名
		$order_no    = $_POST['order_no'];//订单号
		$me_pri      = $_POST['me_pri'];//我们网站生成的金额,参与签名的,跟实际金额有差异

        
            if( $_POST['status'] == 'y' ) {
                //支付成功
                $out_trade_no = $order_no;
                $d = M('recharge')->where(array('order_no'=>$out_trade_no))->find();
                $trade_no = $order_no;
				$pay_model = new PayModel();
                $pay_model->pay_vip_success($d['id'], $d['payment_type'], $trade_no);
                /*if( substr($out_trade_no, 0, 3) == 'VIP' ) {
                    $pay_model = new PayModel();
                    $pay_model->pay_vip_success($d['id'], $d['payment_type'], $trade_no);
                } else {
                    $pay_model = new PayModel();
                    $pay_model->pay_order_success($d['id'], $d['payment_type'], $trade_no);
                }*/
                echo "success";

            } else {
                echo "支付失败";
                exit;
            }
        
    }


private function notify_sign($paydata)
{
    if (!is_array($paydata)) {
        exit("data错误");
    }
    $str_sign="appkey={$paydata['appkey']}&order_no={$paydata['order_no']}&secretkey=3009c5599e35993883a49925e923fdf9&me_pri={$paydata['me_pri']}&uid={$paydata['uid']}&";
    $sign=md5($str_sign);
    return $sign;
}

}