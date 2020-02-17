<?php
namespace Common\Model;
/**
 * 云代付
 * http://www.yundaifu.com/view/help.html?help_type=api#tab1_1
 */
class YundaifuModel extends BaseModel{

    protected $appid;
    protected $key;
    const HOST_URL = "http://api.yundaifu.com/api/api/withdraw";

    public function __construct() {
        $this->appid = sp_cfg('yundaifu_appid');
        $this->key = sp_cfg('yundaifu_key');

        if( empty($this->appid) || empty($this->key) ) {
            return false;
        }
    }

    public function tixian($tixian_id, $bank_user,$bank_number, $price,&$ret_data)
    {
        //支付宝提现
        $parameter = array(
            "appid" => $this->appid,//平台ID号
            "channel" => 'ali',//用户已购买的支付渠道 ali:支付宝,wx:微信,bank:银联
            "order_no" => $tixian_id,  //用户订单号，必须在用户的系统内唯一
            "amount" => $price, // 订单总金额，单位为元
            "description" => '申请提现', // 订单描述
            "recipient_account_type" => 'ALIPAY_LOGONID',//收款方账户类型。ALIPAY_USERID：支付宝账号对应的支付宝唯一用户号，以 2088 开头的 16 位纯数字组成；ALIPAY_LOGONID：支付宝登录号，支持邮箱和手机号格式。
            "recipient_account" => $bank_number,//根据recipient_account_type填写收款人支付宝账号
            "recipient_name" => $bank_user, //收款人姓名
        );

        /*ksort($parameter); //重新排序$parameter数组
        reset($parameter); //内部指针指向数组中的第一个元素
        $str = '';
        foreach ($parameter AS $key => $val) {
            if ($val == '') continue;
            $str .= "&$key=$val";  //拼接为url参数形式
        }
        $sign = md5($str . self::KEY);// MD5签名*/

        $sign = $this->sign($parameter, $this->key);
        $parameter['sign'] = $sign;
        return $this->http_post($parameter,$ret_data);
    }
        /**
     * CURL发送
     * @param $mobile
     * @param $msg
     * @param string $extno
     * @param null $plansendtime
     */
    public function http_post($data,&$ret_data)
    {
        $url = self::HOST_URL;
        $data = http_build_query($data);
        $request = curl_init($url);
        curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
        curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
        $result = curl_exec($request); // execute curl post and store results in $auth_ticket
        curl_close ($request);
        $result = json_decode($result, true);
        if( $result['code'] == '40011' ) {
            $ret_data = $result;
            return true;
        } else {
            $ret_data = $result;
            return false;
        }
    }


    function sign($data,$key){
        $data = $this->argSort($data);
        $data = $this->createLinkstring($data);
        $sign = strtoupper(md5($data.'&key='.$key));

        return $sign;
    }
    function argSort($para) {

        ksort($para);
        reset($para);
        return $para ;
    }
    function createLinkstring($para) {

        $arg  = "";

        foreach ($para as $key=>$val) {
            $arg.=$key."=".$val."&";
        }

        $arg = substr($arg,0,strlen($arg)-1);

        if( get_magic_quotes_gpc()){ $arg = stripslashes( $arg);}

        return $arg;
    }

}