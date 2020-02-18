<?php
namespace Common\Model;

class PayModel extends BaseModel{
    /**
     * 支付成功
     * @param $order_id
     * @param $platform
     * @param $platform_no
     * @return int
     */
    public function pay_order_success($order_id,$platform,$platform_no) {
        $recharge = M('recharge')->find($order_id);
        $member_id = $recharge['member_id'];
        if( empty($recharge) || $recharge['is_pay'] == 1 ) {
            return 0;
        }

        //更新为已支付
        M('recharge')->where(array('id'=>$recharge['id']))->setField('is_pay',1);

        //记录支付记录
        $data = array();
        $data['type'] = 0;//充值
        $data['order_id'] = $order_id;
        $data['order_no'] = $recharge['order_no'];
        $data['member_id'] = $recharge['member_id'];
        $data['price'] = $recharge['price'];
        $data['create_time'] = time();
        $data['payment_type'] = $platform;
        $data['platform_no'] = $platform_no;
        M('pay')->add($data);

        //充值金币
        $model_member = new MemberModel();
        $result = $model_member->incPrice($member_id,$recharge['price'],1,'充值',$recharge['id']);
        if( $result ) {
            //信息提示
            $noticeModel = new NoticeModel();
            $noticeModel->addNotice($recharge['member_id'], "充值成功，充值金额{$recharge['price']}");
        }
        return 1;
    }

    /**
     * 在线支付升级VIP成功后的处理逻辑
     * @param $order_id 订单号
     * @param $platform 渠道
     * @param $platform_no 渠道单号
     */
    public function pay_vip_success($order_id,$platform,$platform_no) {

        $recharge = M('recharge')->find($order_id);
        if( empty($recharge) || $recharge['is_pay'] == 1 ) {
            return ;
        }

        //记录支付记录
        $data = array();
        $data['type'] = 1;//购买VIP
        $data['order_id'] = $order_id;
        $data['order_no'] = $recharge['order_no'];
        $data['member_id'] = $recharge['member_id'];
        $data['price'] = $recharge['price'];
        $data['create_time'] = time();
        $data['payment_type'] = $platform;
        $data['platform_no'] = $platform_no;
        M('pay')->add($data);

        //等级信息
        $member_level = LevelModel::get_member_level();


        //用户基本信息
        $member_data = M('member')->field('id,username,phone,p1,p2,p3,level')->where(array('id'=>$recharge['member_id']))->find();
        /*if( $member_data['level'] > 0 ) {
            //如果用户已经升过级，不再给返利 只升级会员 直接返回
            $res = M('member')->where(array('id'=>$recharge['member_id']))->setField('level', $recharge['level']);
            if( $res ) {
                //发送信息
                $member_level = LevelModel::get_member_level();
                $noticeModel = new NoticeModel();
                $msg = sprintf(sp_cfg('vip_msg'),$member_level[$recharge['level']]['name']);
                $noticeModel->addNotice($recharge['member_id'], $msg, true, $member_data['phone']);
            }
            return ;
        }*/

        //更新为已支付
        M('recharge')->where(array('id'=>$recharge['id']))->setField('is_pay',1);
        $rebate_price_1 = $member_level[$recharge['level']]['rebate_price_1']; //一级提成
        $rebate_price_2 = $member_level[$recharge['level']]['rebate_price_2']; //二级提成
        $rebate_price_3 = $member_level[$recharge['level']]['rebate_price_3']; //三级提成

        //升级用户为会员
        M('member')->where(array('id'=>$recharge['member_id']))->setField('level', $recharge['level']);

        if( $member_data['p1']>0 ) {
            $tuij_pac1 = cacu_pac($member_data['p1'],1,1);
            if( $tuij_pac1>0 ) {
                    $price_1 =  $recharge['price'] * $tuij_pac1/100;
                    $price_1 = sprintf("%.2f", $price_1);

                $this->add_sale($order_id, $price_1, $member_data['p1'], 3, '推荐会员[一级]提成，来源用户'.$member_data['username'], $member_data['id'] );
            }
        }

        if( $member_data['p2']>0 ) {
            $tuij_pac2 = cacu_pac($member_data['p2'],1,2);
            if( $tuij_pac2>0 ) {
                $price_2 =  $recharge['price'] * $tuij_pac2/100;
                $price_2 = sprintf("%.2f", $price_2);

                $this->add_sale($order_id, $price_2, $member_data['p2'], 3, '推荐会员[二级]提成，来源用户'.$member_data['username'], $member_data['id'] );
            }
        }


        if( $member_data['p3']>0 ) {
            $tuij_pac3 = cacu_pac($member_data['p3'],1,3);
            if( $tuij_pac3>0 ) {
                $price_3 =  $recharge['price'] * $tuij_pac3/100;
                $price_3 = sprintf("%.2f", $price_3);

                $this->add_sale($order_id, $price_3, $member_data['p3'], 3, '推荐会员[三级]提成，来源用户'.$member_data['username'], $member_data['id'] );
            }
        }



        //信息提示
        $noticeModel = new NoticeModel();
        $msg = sprintf(sp_cfg('vip_msg'),$member_level[$recharge['level']]['name']);
        $noticeModel->addNotice($recharge['member_id'], "恭喜您，您的VIP等级已升级为{$msg}", true, $member_data['phone']);
    }


    /**
     * 给会员添加收益
     * @param $order_id
     * @param $price
     * @param $member_id
     * @param $type
     * @param $remark
     * @param int $from_member_id
     */
    public function add_sale($order_id,$price,$member_id,$type,$remark,$from_member_id=0)
    {
        $data['member_id'] = $member_id;
        $data['from_member_id'] = $from_member_id;
        $data['order_id'] = $order_id;
        $data['price'] = $price;
        $data['remark'] = $remark;
        $data['create_time'] = time();
        $data['type'] = $type;
        $result = M('sale_list')->add($data); //收益记录
        if( $result ) {
            //添加金额变动记录
            $model_member = new MemberModel();
            $model_member->incPrice($member_id,$price,$type,$remark,$result);
        } else {
            throw_exception('添加收益失败');
        }
    }
}
