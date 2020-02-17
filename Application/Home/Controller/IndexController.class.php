<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
use Org\Net\Mobile;
use Common\Model\LevelModel;

class IndexController extends HomeBaseController{

    public function index()
    {


        //判断客户端
        $dev = new Mobile();
        //AndroidOS
        if( $dev->isMobile() ) {
            if( $dev->is('iOS') ) {
                $client_dev = 'ios';
            } else {
                $client_dev = 'android';
            }
            $is_mobile = 1;
        } else {
            $client_dev = 'pc';
            $is_mobile = 0;
        }
        $this->assign('is_mobile', $is_mobile);
        $this->assign('client_dev', $client_dev);

        //记录访问的客户端信息
        // http://www.??.com?platform=app&platform_type=android&version=1.0.0
        $platform = I('get.platform');
        if( !empty($platform) ) {
            $member_client_info['platform'] = I('get.platform');
            $member_client_info['platform_type'] = $client_dev;
            $member_client_info['version'] = I('get.version');
            session('member_client_info', $member_client_info);
        } else {
            //session('member_client_info.platform_type',$client_dev);
            $member_client_info = session('member_client_info');
        }
        $this->assign('member_client_info', $member_client_info);
        $map = array();
        $map['status'] = 1;
        $task_list = M('task')->field('id,title,level,price,create_time,max_num,apply_num,max_num-apply_num as leftnum, tasklb')->where($map)->order('id desc')->limit(1000)->select();
        $level_list = LevelModel::get_member_level();
 	    $this->assign ( 'level', $level_list );
        $this->assign('task_list', $task_list);

        //个人账户信息
        $member_price = M('member')->where(array('id'=>$this->get_member_id()))->getField('price');
        $this->assign('member_price', $member_price);

        //平台用户、发放金额
        $member_total = floatval(sp_cfg('member_total')) + M('member')->count();
        $this->assign('member_total', $member_total);
        $price_total = floatval(sp_cfg('price_total')) + M('member_tixian')->where(array('status'=>1))->sum('price');
        $this->assign('price_total', $price_total);

        //公告
        $page_notice_list = M('page')->field('id,title,update_time')->where(array('recommend'=>1))->order('update_time asc')->limit(3)->select();
        $this->assign('page_notice_list', $page_notice_list);

        $title = sp_cfg('website');
        $this->assign('title', $title);

        $this->display();
    }

    /**
     * webuploader 上传文件
     */
    public function ajax_upload(){
        // 根据自己的业务调整上传路径、允许的格式、文件大小
     //   ajax_upload('/Uploads/images/');
    $upload = new \Think\Upload();// 实例化上传类
    $upload->maxSize   =     8145728 ;// 设置附件上传大小
    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
    $upload->savePath  =     ''; // 设置附件上传（子）目录
    // 上传文件 
    $info   =   $upload->upload();
    if(!$info) {// 上传错误提示错误信息
            $data['error_info']=$upload->getError();
            echo json_encode($data);
    }else{// 上传成功
       $data = array('name'=>"/Uploads/".$info['file']['savepath'].$info['file']['savename'],'oldname'=>$info['file']['name']);
      echo json_encode($data);
    }
    }

    /**
     * webuploader 上传demo
     */
    public function webuploader(){
        // 如果是post提交则显示上传的文件 否则显示上传页面
        if(IS_POST){
            p($_POST);die;
        }else{
            $this->display();
        }
    }
}

