<?php
namespace Mobile\Controller;
use Think\Controller;
use Home\Classlogic\Weixin_accountClasslogic;
class PayController extends MobileBaseController {

    public $member_id = 0;
    public $openid = null;
    public $member = array();


    /*
    * 初始化操作
    */
    public function _initialize(){
        parent::_initialize();

        $member = $_SESSION['member'];
        $this->member = $member;
        $this->member_id = $member['member_id'];
        $this->openid = empty($member['openid'])?'':$member['openid'];
        if($_SESSION['token'] != $member['token']){
            parent::base_get_openid();
        }
        //重新赋值
        if(!empty($_SESSION['openid']) || !empty($_SESSION['member_id'])){
            $member = M('member')->where(array('token'=>$_SESSION['token'],'openid'=>$_SESSION['openid']))->find();
            $this->member = $member;
            $this->member_id = $member['member_id'];
            $this->openid = empty($member['openid'])?'':$member['openid'];
        }
        $this->assign('member', $member); //存储用户信息
        if (!$this->member_id || !$this->openid) {
            //重新获取微信openid 进行操作
        }
    }



    public function pay(){

        $distribution_config = M('distribution')->order('id desc')->limit(1)->find();
        $bili = $distribution_config['charge_rmb']/$distribution_config['charge_coin'];

        $wechat = M('wechat_config')->where(array('is_default'=>1))->find();

        $order = array(
            'charge_money'=>floor($_GET['money']),
            'member_id'=>$_SESSION['member_id'],
            'openid'=>$_SESSION['openid'],
            'order_sn'=>getOrderSn(),
            'coin'=>$_GET['coin'],
            'appid'=>$wechat['appid'],
            'charge_proportion'=>$bili,
            'status'=>0,
            'pay_code'=>'weixin',
            'createtime'=>time(),
        );
        $order['order_id'] = M('order')->add($order);

        /**
         * 跳转支付
         */
        $ps = array(
            'appid'   =>$wechat['appid'],
            'order_id'  =>$order['order_id'],
            'openid'=>$_SESSION['openid']
        );
        $sl = base64_encode(json_encode($ps));
        $class_pay = new Weixin_accountClasslogic($wechat['id']);
        $class_pay->set_order_data($order);
//        $pay_param = $class_pay->test_wechat_pay_build($_SESSION['openid']);
        $pay_param = $class_pay->wechat_pay_build();
        if(is_error($pay_param)){
            html_tips($pay_param['message']);
            exit();
        }
        $pay_param_str = base64_encode(json_encode($pay_param));
        $url = SITE_URL . "/payment/wxpay/pay.php?auth=".$pay_param_str."&ps=".$sl;
        header("location: $url");
        exit();

    }

    public function pay_return(){

    }

    public function pay_cancel(){

    }

}
