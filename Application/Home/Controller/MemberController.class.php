<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
use Common\Model\LevelModel;
use Common\Model\MemberModel;
use Common\Model\NoticeModel;
use Common\Model\PayModel;
use Common\Model\PhonecodeModel;
use Common\Model\PostsApplyModel;
use Common\Model\YundaifuModel;
use Think\Model;

class MemberController extends HomeBaseController{

    protected $member_id;

    /**
     * 初始化方法
     */
    public function _initialize(){
        parent::_initialize();

        if( !$this->is_login() ) {
            $this->redirect('Public/login');
            //$this->error('请先登陆', U('Public/login'));
        }

        $this->member_id = $this->get_member_id();
    }

    /**
     * 个人中心首页
     */
    public function index()
    {
        $data = M('member')->find($this->member_id);
        $this->assign('level_name', $this->get_level_name($data['level']));
        $this->assign('data', $data);
        //今日佣金
        $total_price['today'] = M('sale_list')->where("member_id={$this->member_id} and TO_DAYS(from_unixtime(create_time)) = TO_DAYS(now())")->sum('price');
        $total_price['month'] = M('sale_list')->where("member_id={$this->member_id} and DATE_FORMAT(from_unixtime(create_time),'%Y%m') = DATE_FORMAT(CURDATE(),'%Y%m')")->sum('price');
        $total_price['all'] = M('sale_list')->where("member_id={$this->member_id}")->sum('price');
        $this->assign('total_price', $total_price);

        //下级新增人数
        $total_team['today'] = M('member')->where(" (p1={$this->member_id} or p2={$this->member_id} or p3={$this->member_id}) and (TO_DAYS(from_unixtime(create_time)) = TO_DAYS(now())) ")->count();
        $total_team['month'] = M('member')->where(" (p1={$this->member_id} or p2={$this->member_id} or p3={$this->member_id}) and (DATE_FORMAT(from_unixtime(create_time),'%Y%m') = DATE_FORMAT(CURDATE(),'%Y%m'))")->count();
        $total_team['all'] = M('member')->where("p1={$this->member_id} or p2={$this->member_id} or p3={$this->member_id}")->count();
        $this->assign('total_team', $total_team);

        //新消息条数
        if( $data['role'] == 1 ) {
            $role_type = 2;
        } else {
            $role_type = 1;
        }
        $notice_view_time = intval($data['notice_view_time']);
        $where = "(( member_id = {$this->member_id} ) or ( (is_system = 1 and role_type = 0) or ( is_system = 1 and role_type = {$role_type} ) ) ) and create_time > {$notice_view_time} ";
        $notice_num = M('notice')->where($where)->count();
        $this->assign('notice_num', intval($notice_num));

        $this->display();
    }

    /**
     * 系统消息
     */
    public function notice()
    {
        $member = M('member')->field('notice_view_time,role')->find($this->member_id);
        if( $member['role'] == 1 ) {
            $role_type = 2;
        } else {
            $role_type = 1;
        }
        $member_id = $this->member_id;
        $where = "( member_id = {$member_id} ) or ( (is_system = 1 and role_type = 0) or ( is_system = 1 and role_type = {$role_type} ) )";

        $list = M('notice')->where($where)->order('id desc')->limit(100)->select();
        foreach( $list as &$_list ) {
            if( $_list['is_system'] == 1 ) {
                $view_style = $member['notice_view_time'] < $_list['create_time'] ? 0 : 1;
            } else {
                $view_style = $_list['has_view'];
            }
            $_list['view_style'] = $view_style;
        }

        $this->assign('list', $list);

        //将消息都设置为已读
        M('notice')->where(array('member_id'=>$this->member_id))->save(array('has_view'=>1));
        M('member')->where(array('id'=>$this->member_id))->setField('notice_view_time', time());

        $this->display();
    }


    /**
     * 个人信息
     */
    public function info()
    {
        $this->display();
    }

    /**
     * 编辑个人信息
     */
    public function info_edit()
    {
        if( IS_POST ) {
            $field = I('field');
            $value = I('value');
            $data['id'] = $this->member_id;
            $data[$field] = $value;

            if( $field == 'idc' ) {
                if( !is_idcard($value) ) {
                    $this->error('身份证号码不正确');
                }
            }

            //更新地址
            if( $field == 'address' ) {
                $city_ids = explode(',',I('post.city_ids'));
                $city_names = explode(' ',I('post.city_names'));
                if( count($city_ids) != 3 || count($city_names) != 3 ) {
                    $this->error('请选择地址信息');
                }
                $data['province_id'] = $city_ids[0];
                $data['city_id'] = $city_ids[1];
                $data['area_id'] = $city_ids[2];
                $data['province'] = $city_names[0];
                $data['city'] = $city_names[1];
                $data['area'] = $city_names[2];
            }

            //更新银行卡信息
            if( $field == 'bank_number' ) {
                $data['bank_name'] = I('post.bank_name');
                $data['subbranch_name'] = I('post.subbranch_name');
                $data['bank_user'] = I('post.bank_user');
            }

            $res = M('member')->save($data);
            if( $res ) {

                $member = M('member')->find($this->member_id);
                $member[$field] = $value;
                session('member', $member);

                if( I('f') == 'tixian' ) {
                    $this->success('更新成功', U('Member/tixian'));
                } else {
                    $this->success('更新成功', U('info'));
                }


            } else {
                $this->error('更新失败');
            }

        } else {
            $field_info = array(
                'nickname' => '更新昵称',
                'phone' => '更新手机号码',
                'idc' => '更新身份证信息',
                'bank_number' => '更新银行卡信息',
                'address' => '更新地址信息',
                'intro' => '个人说明',
                'sex' => '性别',
            );

            $field = I('get.field');
            $member = session('member');
            if( empty($member['bank_name']) ) $member['bank_name'] = '支付宝';
            $value = $member[$field];

            $this->assign('field',$field);
            $this->assign('value',$value);
            $this->assign('info',$field_info[$field]);

            if( $field == 'address' ) {
                $city_ids = array($member['province_id'],$member['city_id'],$member['area_id']);
                if( !($member['province_id'] > 0 ) ) {
                    $city_ids[0] = M('area')->where(array('title'=>$member['province']))->getField('area_id');

                    if( !($member['city_id'] >0 ) ) {
                        $city_ids[1] = M('area')->where(array('title'=>$member['city']))->getField('area_id');
                    }
                }
                //$city_ids = implode(',', $city_ids);
                $this->assign('city_ids',$city_ids);

                /*$city_names = array($member['province'],$member['city'],$member['area']);
                $city_names = implode(',', $city_names);
                $this->assign('city_names',$city_names);*/

                $tpl = "info_edit_address";
            } elseif( $field == 'bank_number' ) {
                $banks = array(
                    "支付宝"
                );
                $this->assign('banks',$banks);
                $tpl = 'info_edit_bank';
            } elseif( $field == 'sex' ) {
                $tpl = 'info_edit_sex';
            } else {
                $tpl = '';
            }

            $this->display($tpl);
        }
    }

    /**
     * 我的申请
     */
    public function apply()
    {
        $map = array();
        $map['member_id'] = $this->member_id;

        $model = M('task_apply');
        $count = $model->where($map)->count();
        $page = sp_get_page_m($count, 10);//分页
        $firstRow = $page->firstRow;
        $listRows = $page->listRows;

        $list = $model->alias('a')
            ->join(C('DB_PREFIX').'task as b on a.task_id = b.id','left')
            ->where(array('a.member_id'=>$this->member_id))
            ->field('a.*,b.title')
            ->order('a.id desc')->limit("$firstRow , $listRows")
            ->select();

        $this->assign("Page", $page->show());
        $this->assign("list", $list);
        $this->assign('count', $count);

        //已经申请的状态
        $apply_status = C('APPLY_STATUS');
        $this->assign ( 'apply_status', $apply_status );

        $this->display();
    }

    /**
     * 申请详情
     */
    public function apply_show()
    {
        $id = I('get.id');
        $apply_data = M('task_apply')->find($id);

        $task_id = $apply_data['task_id'];
        $apply_data['task_title'] = M('task')->where(array('id'=>$task_id))->getField('title');
        $apply_status = C('APPLY_STATUS');
        $apply_data['apply_status'] = $apply_status[$apply_data['status']];
        $this->assign("apply_data", $apply_data);

        $this->display();
    }

    /**
     * 账单
     */
    public function bill()
    {
        $weekarray=array("日","一","二","三","四","五","六");

        $map['member_id'] = $this->member_id;
        //$map['is_pay'] = 1;
        $list = $this->_list('member_price_log', $map, '', 'id desc', 500);
        $news_list = array();
        foreach( $list as $k=>$v ) {
            $day = date('Y-m', $v['create_time']);
            $news_list[$day][] = $v;
        }
        foreach( $news_list as &$_list ) {
            foreach( $_list as &$_list2 ) {
                $_list2['w'] = $weekarray[date('w', $_list2['create_time'])];
            }
        }
        $this->assign('news_list', $news_list);
        $this->display();
    }

    /**
     * 余额
     */
    public function balance()
    {
        $data = $this->get_member_data();
        $balance = $data['price'];
        $this->assign('balance', $balance);
        $this->display();
    }

    /**
     * 充值界面
     */
    public function recharge_do()
    {
        if( IS_POST ) {
            $price = I('price');
            if( !is_numeric($price) || !($price > 0) ) {
                $this->error('价格必须为数字，且大于0');
            }
			
            $payment_type = I('payment_type');
            $data = array();
            $order_no = $this->create_order_no();
            $data['order_no'] = $order_no;
            $data['level'] = I('level');
            $data['member_id'] = $this->member_id;
            //$data['msg'] = strip_tags(I('msg'));
            $data['price'] = $price;
            $data['create_time'] = time();
            $data['is_pay'] = 0;
            $data['payment_type'] = $payment_type;
            $insert_id = M('recharge')->add($data);
            if( $insert_id ) {
            
            $data1 = array(
			    'appkey'=>'21337_dee15a4609b1d0d00b9d20b7e4ba34dd',//你的appkey
			    'pay_way'=>'wechat',//wechat为微信支付,alipay为支付宝
			    'uid'=>'1',//你的用户id
			    'total_fee'=>$data['price'],//你的金额
			    'order_no'=>$data['order_no'],//你的订单号
			    'pay_title'=>'微信支付',//你的订单号
			    'me_param'=>'',//其他参数,可返回回调里面
			    'notify_url'=>'http://baidu.com',//异步回调地址
			    'me_back_url'=>'http://baidu.com',//支付成功后返回
			    'me_eshop_openid'=>'',//付款用户openid
			    'me_party'=>'',//根据其他支付插件,异步回调返回同样参数,比如填写codepay,码支付,我们异步回调的时候就按码支付的回调参数返回
			    'sign'=>''//签名
			    );
			    $data1['sign'] =$this->pay_sign($data1);
                //跳转到微信支付
               // $this->redirect('Api/Iweipay/pay',array('order_no'=>$order_no,'payment_type'=>$payment_type));
             //  var_dump($_SERVER['HTTP_HOST']);
               $urls = 'http://'.$_SERVER['HTTP_HOST'].'/fastpay/demo/pay.php?order_no='.$order_no.'&payment_type='.$payment_type.'&money='.$data['price'];
               //var_dump($urls);die;
               header("Location:".$urls);
                //$this->redirect('Api/Weixinpay/pay',array('out_trade_no'=>$order_no));
                
            }
        } else {
            $this->display();
        }
    }
 
 private function pay_sign($paydata)
{
    if (!is_array($paydata)) {
        exit("data错误");
    }
    $str_sign="appkey={$paydata['appkey']}&order_no={$paydata['order_no']}&secretkey=3009c5599e35993883a49925e923fdf9&total_fee={$paydata['total_fee']}&uid={$paydata['uid']}&";
    $sign=md5($str_sign);
    return $sign;
}
    //生成订单号
    private function create_order_no() {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2017] . $this->member_id . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }

    /**
     * 申请提现
     */
    public function tixian()
    {
        if( IS_POST ) {
            $member_data = M('member')->field('price,bank_name,bank_user,bank_number,first_tixian')->find($this->member_id);

            $today_tixian_num = intval(M('member_tixian')->where("member_id={$this->member_id} and TO_DAYS(from_unixtime(create_time)) = TO_DAYS(now())")->count());
            if( $today_tixian_num>0 ) {
                $this->error('每天只能提现一次');
            }

            if( empty($member_data['bank_name']) || empty($member_data['bank_user']) || empty($member_data['bank_number']) ) {
                $this->error('请先完善您的收款渠道');
            }

            $member_price = $member_data['price'];
            $price = floatval(I('post.price'));
            if( !($member_price > 0) ) {
                $this->error('没有可提现的余额');
            }
            if( !($price>0) ) {
                $this->error('提现金额不能为0');
            }
            if( $price > $member_price ) {
                $this->error('余额不足');
            }

            $min_tixian_price = floatval(sp_cfg('min_tixian_price'));
            $new_member_tixian_price = floatval(sp_cfg('new_member_tixian_price'));
            //非首次提现
            if( $member_data['first_tixian']==1 ) {
                if( $price==$new_member_tixian_price ) {
                    $this->error("{$new_member_tixian_price}元提现为新用户专享");
                }

                if( !($price >= $min_tixian_price) ) {
                    $this->error('提现金额不能少于'.$min_tixian_price);
                }

                if( $price%$min_tixian_price != 0 ) {
                    $this->error('提现金额必须为'.$min_tixian_price.'的倍数');
                }
            } else {
                //首次提现
                if( $price!=$new_member_tixian_price ) {
                    if( !($price >= $min_tixian_price) ) {
                        $this->error('提现金额不能少于'.$min_tixian_price);
                    }

                    if( $price%$min_tixian_price != 0 ) {
                        $this->error('提现金额必须为'.$min_tixian_price.'的倍数');
                    }
                }
            }

            $data = array();
            $data['member_id'] = $this->member_id;
            $data['price'] = $price;
            $data['create_time'] = time();
            $data['status'] = 0;
            $data['charge'] = sp_cfg('charge');
            if( $price==$new_member_tixian_price ) {
                $actual_price = $price;
            } else {
                $actual_price = $price - $price*sp_cfg('charge')/100;
            }

            $data['actual_price'] = $actual_price;
            $inster_id = M('member_tixian')->add($data);
            if( $inster_id ) {
                $model = new MemberModel();
                $res = $model->decPrice($this->member_id, $price, 100, '申请提现');
                if( $res ) {
                    //设置用户为非首次提现用户
                    if( $price==$new_member_tixian_price ) {
                        M('member')->where(array('id'=>$this->member_id))->setField('first_tixian', 1);
                    }

                    //自动转账最大金额
                    $auto_max_price = floatval(sp_cfg('auto_max_price'));
                    if( $price <= $auto_max_price ) {
                        //$tx_result = alipay_FundTrans($inster_id, $member_data['bank_number'], $actual_price);
                        $YundaifuModel = new YundaifuModel();
                        $tx_result = $YundaifuModel->tixian($inster_id, $member_data['bank_user'], $member_data['bank_number'], $actual_price, $ret_data);
                        if( $tx_result ) {
                            //设置为已审核
                            M('member_tixian')->where(array('id'=>$inster_id))->setField('status', 1);

                            $memberModel = new MemberModel();
                            $memberModel->txShenHe($inster_id, 1);

                            $noticeModel = new NoticeModel();
                            $noticeModel->addNotice($this->member_id, '系统已转账，转账流水号'.$ret_data['data']['payment_no']);

                            $this->success('已转账，请留意', U('index'));
                        } else {
                            $this->success('提交申请成功，等待管理员审核', U('index'));
                        }
                    } else {
                        $this->success('提交申请成功，等待管理员审核', U('index'));
                    }


                } else {
                    $this->error('提交失败');
                }
            } else {
                $this->error('提交失败');
            }
        } else {

            $data = M('member')->find($this->member_id);
            $data['bank_number_last'] = substr($data['bank_number'],-4);
            $this->assign('data', $data);
            $this->assign('min_tixian_price', floatval(sp_cfg('min_tixian_price')));
            $this->assign('new_member_tixian_price', floatval(sp_cfg('new_member_tixian_price')));

            //是否能继续提现
            $today_tixian_num = intval(M('member_tixian')->where("member_id={$this->member_id} and TO_DAYS(from_unixtime(create_time)) = TO_DAYS(now())")->count());
            if( $today_tixian_num>0 ) {
                $this->assign('can_tixian', 0);
            } else {
                $this->assign('can_tixian', 1);
            }

            $this->display();
        }
    }

    public function tixian_log()
    {
        $TIXIAN_STATUS = C('TIXIAN_STATUS');

        $status = I('get.status');
        $start_date = I('get.start_date');
        $end_date = I('get.end_date');

        $map = array();
        $map['member_id'] = $this->member_id;

        if( $status != '' ) $map['a.status'] = $status;

        //搜索时间
        if( !empty($start_date) && !empty($end_date) ) {
            $start_date = strtotime($start_date . "00:00:00");
            $end_date = strtotime($end_date . "23:59:59");
            $map['_string'] = "( a.create_time >= {$start_date} and a.create_time < {$end_date} )";
        }

        $model = M('member_tixian')->alias('a');
        $count = $model->where($map)->count();
        $page = sp_get_page($count, 20);//分页
        $firstRow = $page->firstRow;
        $listRows = $page->listRows;

        $list = M('member_tixian')->alias('a')->join(C('DB_PREFIX').'member as c on a.member_id = c.id','left')
            ->where($map)
            ->field('a.*,c.nickname,c.phone,c.bank_name,c.bank_user,c.bank_number')
            ->order('a.id desc')->limit("$firstRow , $listRows")
            ->select();
        foreach( $list as &$_list ) {
            $_list['status_text'] = $TIXIAN_STATUS[$_list['status']];
            /*$price = abs($_list['price']);
            $_list['price'] = $price;
            $_list['charge'] = sp_cfg('charge');
            $_list['actual_price'] = $price - $price * sp_cfg('charge')/100;*/
        }
        $this->assign('list',$list);
        $this->assign('tixian_status',$TIXIAN_STATUS);
        $this->assign('get',$_GET);
        $this->display();
    }

    /**
     * 生成二维码
     */
    public function qrcode(){
        $url  = U('Index/index',array('smid'=>$this->member_id),'',true);
        qrcode($url,6);
    }

    /**
     * 修改密码
     */
    public function password()
    {
        if( IS_POST ) {
            $d = I('post.');

            if( empty($d['old_password']) ) {
                $this->error('请输入当前密码。');
            }
            if( empty($d['password']) ) {
                $this->error('请输入当前密码。');
            }
            if( empty($d['repassword']) ) {
                $this->error('请再次输入新密码。');
            }
            if( $d['password'] != $d['repassword'] ) {
                $this->error('两次密码不一致。');
            }
            //短信验证码
            /*if( !PhonecodeModel::check_phone_code($code_type, $d['phone'], $code) ) {
                $this->error('短信验证码错误。');
            }*/

            $password = sp_encry($d['password']);

            $old_password = M('member')->where(array('id'=>$this->member_id))->getField('password');
            if( $old_password != sp_encry($d['old_password']) ) {
                $this->error('当前密码不正确。');
            }


            $res = M('member')->where(array('id'=>$this->member_id))->setField('password', $password);
            if( $res ) {
                $this->success('修改成功', U('index'));
            } else {
                $this->error('密码修改失败');
            }
        } else {
            $this->display();
        }
    }

    /**
     * 升级VIP
     */
    public function vip()
    {
        if( IS_POST ) {
            $member_level = LevelModel::get_member_level();
            $level = intval(I('post.level'));
            $price = $member_level[$level]['price'];
            if( !($level > 0) ) {
                $this->error('请选择要升级的级别');
            }
            if( !($price > 0) ) {
                $this->error('价格参数错误');
            }

            $member_price = M('member')->where(array('id'=>$this->member_id))->getField('price');
            if( $price>$member_price ) {
                $this->error('余额不足，请充值', U('recharge_do'));
            }
            //升级用户为会员
            $res = M('member')->where(array('id'=>$this->member_id))->setField('level', $level);
            if( $res ) {
                //扣除用户余额
                $model_member = new MemberModel();
                $log_id = $model_member->decPrice($this->member_id, $price,97,'会员升级消费',0);

                $model_pay = new PayModel();
                //等级信息
                $member_level = LevelModel::get_member_level();
                //用户基本信息
                $member_data = M('member')->field('id,username,phone,p1,p2,p3,level')->where(array('id'=>$this->member_id))->find();
                $rebate_price_1 = $member_level[$level]['rebate_price_1']; //一级提成
                $rebate_price_2 = $member_level[$level]['rebate_price_2']; //二级提成
                $rebate_price_3 = $member_level[$level]['rebate_price_3']; //三级提成

                //给直接上级返利
                if( $member_data['p1']>0 ) {
                    if( $rebate_price_1>0 ) {
                        $model_pay->add_sale($log_id, $rebate_price_1, $member_data['p1'], 3, '推荐会员提成，来源用户'.$member_data['username'], $member_data['id'] );
                    }
                }
                //二级返利
                if( $member_data['p2']>0 ) {
                    if( $rebate_price_2>0 ) {
                        $model_pay->add_sale($log_id, $rebate_price_2, $member_data['p2'], 3, '推荐会员提成，来源用户'.$member_data['username'], $member_data['id'] );
                    }
                }
                //三级返利
                if( $member_data['p3']>0 ) {
                    if( $rebate_price_3>0 ) {
                        $model_pay->add_sale($log_id, $rebate_price_3, $member_data['p3'], 3, '推荐会员提成，来源用户'.$member_data['username'], $member_data['id'] );
                    }
                }

                //信息提示
                $noticeModel = new NoticeModel();
                $msg = sprintf(sp_cfg('vip_msg'),$member_level[$level]['name']);
                $noticeModel->addNotice($this->member_id, "恭喜您，您的VIP等级已升级为{$msg}", true, $member_data['phone']);

                $this->success('升级成功', U('index'));
            } else {
                $this->error('升级失败');
            }

        } else {
            $member = $this->get_member_data();
            $member['level_name'] = $this->get_level_name($member['level']);
            $member_level = LevelModel::get_member_level();
            $day_limit_task_num = $member_level[$member['level']]['day_limit_task_num'];
            $today_task_num = M('task_apply')->where("member_id={$this->member_id} and TO_DAYS(from_unixtime(create_time)) = TO_DAYS(now())")->count();
            $this->assign('day_limit_task_num',$day_limit_task_num);
            $this->assign('sy_task_num',$day_limit_task_num-$today_task_num);
            unset($member_level[0]);//不显示免费会员
            $this->assign('member_level',$member_level);
            $this->assign('member',$member);
            $this->display();
        }
    }

    /**
     * 升级VIP
     */
    public function vip2()
    {
        if( IS_POST ) {

            $member_level = LevelModel::get_member_level();
            $level = intval(I('post.level'));
            $price = $member_level[$level]['price'];
            if( !($level > 0) ) {
                $this->error('请选择要升级的级别');
            }
            if( !($price > 0) ) {
                $this->error('价格参数错误');
            }

            $payment_type = I('payment_type');

            $data = array();
            $order_no = 'VIP'.$this->create_order_no();
            $data['order_no'] = $order_no;
            $data['member_id'] = $this->member_id;
            $data['price'] = $price;
            $data['create_time'] = time();
            $data['is_pay'] = 0;
            $data['level'] = $level;
            $data['payment_type'] = $payment_type;
            $insert_id = M('recharge')->add($data);
            if( $insert_id ) {
                $this->redirect('Api/Iweipay/pay',array('order_no'=>$order_no,'payment_type'=>$payment_type));

                //在线支付
                /*if( $payment_type == 'wxpay' ) {
                    //在线支付 个人免签
                    $this->redirect('Api/Paysapi/pay',array('order_no'=>$order_no,'payment_type'=>$payment_type));
                } elseif($payment_type=='alipay') {
                    $data=array(
                        'out_trade_no'=>$order_no,
                        'price'=>$price,
                        'subject'=>"会员升级"
                    );
                    alipay($data);
                } else{
                    $this->error('支付渠道有误');
                }*/



                #$this->redirect('Api/Yipay/pay',array('order_no'=>$order_no,'payment_type'=>$payment_type));
                //线下扫码转账
                //$this->success('提交成功，前往支付',U('pay',array('out_trade_no'=>$order_no)));
            }
        } else {
            $member = $this->get_member_data();
            $member['level_name'] = $this->get_level_name($member['level']);
            $member_level = LevelModel::get_member_level();
            $day_limit_task_num = $member_level[$member['level']]['day_limit_task_num'];
            $today_task_num = M('task_apply')->where("member_id={$this->member_id} and TO_DAYS(from_unixtime(create_time)) = TO_DAYS(now())")->count();
            $this->assign('day_limit_task_num',$day_limit_task_num);
            $this->assign('sy_task_num',$day_limit_task_num-$today_task_num);
            unset($member_level[0]);//不显示免费会员
            $this->assign('member_level',$member_level);
            $this->assign('member',$member);
            $this->display();
        }
    }

    public function pay()
    {
        $out_trade_no = I('get.out_trade_no');
        $data = M('recharge')->where(array('order_no'=>$out_trade_no))->find();
        if( empty($data) ) {
            $this->error('单号不存在');
        }
        $this->assign('data',$data);
        $this->assign('out_trade_no',$out_trade_no);
        $this->display();
    }

    public function pay_screenshot()
    {
        if( IS_POST ) {
            $order_no = I('post.order_no');
            if( empty($order_no) ) {
                $this->error('请选择要提交的订单');
            }

            if( $_FILES ) {
                $file = ajax_upload('/Uploads/pay_image/',1,$this->member_id);
            } else {
                $this->error('请上传任务截图');
            }

            $data['file'] = $file;
            $data['order_no'] = $order_no;
            $data['member_id'] = $this->member_id;
            $data['create_time'] = time();
            $data['status'] = 0;//未处理
            $result = M('recharge_screenshot')->add($data);
            if($result) {
                $this->success('提交成功，等待管理员审核',U('index'));
            } else {
                $this->error('提交失败');
            }
        } else {
            //待支付记录
            $pay_list = M('recharge')->where(array('member_id'=>$this->member_id,'is_pay'=>0))->group('price')->select();
            $this->assign('pay_list',$pay_list);
            $this->display();
        }
    }

    /**
     * 我的团队
     */
    public function team()
    {
        $map = array();
        $rank = intval(I('get.rank',1));

        if( $rank == 2 ) {
            $map['p2'] = $this->member_id;
        } elseif( $rank == 3 ) {
            $map['p3'] = $this->member_id;
        } else {
            $map['p1'] = $this->member_id;
        }

        $model = M('member');
        $count = $model->where($map)->count();
        $page = sp_get_page_m($count, 20);//分页
        $firstRow = $page->firstRow;
        $listRows = $page->listRows;
        $list = $model->field('id,username,head_img,create_time,level')
            ->where($map)
            ->order('level desc,id desc')->limit("$firstRow , $listRows")
            ->select();
        $member_level = LevelModel::get_member_level();
        foreach($list as &$_list) {
            $where = array();
            $where['p1'] = $_list['id'];
            $_list['number'] = M('member')->where($where)->count();
            $_list['level_name'] = $member_level[$_list['level']]['name'];
        }
        $this->assign("Page", $page->show());
        $this->assign("list", $list);
        $this->assign('count', intval($count));
        $this->assign("rank", $rank);

        $this->assign('count_1', M('member')->where(array('p1'=>$this->member_id))->count());
        $this->assign('count_2', M('member')->where(array('p2'=>$this->member_id))->count());
        $this->assign('count_3', M('member')->where(array('p3'=>$this->member_id))->count());

        $this->display();
    }

    //销售提成记录
    public function sale()
    {
        $day = date('Y-m-d');
        $start_date = I('get.start_date');
        $end_date = I('get.end_date');

        //$type = 1;
        $member_id = $this->member_id;

        $where = '';
        //搜索时间
        if( !empty($start_date) && !empty($end_date) ) {
            $_start_date = strtotime($start_date . "00:00:00");
            $_end_date = strtotime($end_date . "23:59:59");
            $where = " and create_time >= {$_start_date} and create_time < {$_end_date} ";
        }
        //日期内的收入
        $sql = "select SUM(price) as total from `dt_sale_list` where `member_id` = {$member_id} {$where}";
        $dao = new Model();
        $sum_data = $dao->query($sql);
        $today_total_price = $sum_data[0]['total'];
        $this->assign("today_total_price", floatval($today_total_price));


        $map = array();
        //$map['a.type'] = $type;
        $map['a.member_id'] = $member_id;
        if( $start_date == $end_date ) {
            $this->assign("show_day", $start_date);
        } else {
            $this->assign("show_day", "{$start_date} ~ {$end_date}");
        }
        $this->assign("start_date", $start_date);
        $this->assign("end_date", $end_date);

        //搜索时间
        if( !empty($start_date) && !empty($end_date) ) {
            $start_date = strtotime($start_date . "00:00:00");
            $end_date = strtotime($end_date . "23:59:59");
            $map['_string'] = "( a.create_time >= {$start_date} and a.create_time < {$end_date} )";
        }

        $model = M('sale_list');
        $count = $model->alias('a')->where($map)->count();
        $page = sp_get_page_m($count, 50);//分页
        $firstRow = $page->firstRow;
        $listRows = $page->listRows;
        $list = $model->alias('a')
            ->join(C('DB_PREFIX').'member as b on a.from_member_id = b.id','left')
            ->where($map)
            ->field('a.*,b.username,b.phone')
            ->order('a.id desc')->limit("$firstRow , $listRows")
            ->select();
        $this->assign("Page", $page->show());
        $this->assign("list", $list);
        $this->assign('count', intval($count));
        $this->assign("type", $type);
        $this->assign("day", $day);

        $this->display();
    }

    //用户信息
    private function get_member_data()
    {
        $data = M('member')->find($this->member_id);
        return $data;
    }

    /**
     * 会员等级名称
     * @param $level
     */
    private function get_level_name($level) {
        $member_level = LevelModel::get_member_level();
        return $member_level[$level]['name'];
    }
}

