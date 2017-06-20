<?php
namespace Mobile\Controller;
use Mobile\Classlogic\PaymentClasslogic;
use Think\Controller;
use Home\Classlogic\Weixin_accountClasslogic;
use Mobile\Classlogic\WxpayClasslogic;
use Mobile\Classlogic\ThirdpayClasslogic;
//class PaymentController extends MobileBaseController {
class PaymentController extends Controller {

    public $member_id = 0;
    public $openid = null;
    public $member = array();


    /*
    * 初始化操作
    */
//    public function _initialize(){
//        parent::_initialize();
//
//        $member = $_SESSION['member'];
//        $this->member = $member;
//        $this->member_id = $member['member_id'];
//        $this->openid = empty($member['openid'])?'':$member['openid'];
//        if($_SESSION['token'] != $member['token']){
//            parent::base_get_openid();
//        }
//        //重新赋值
//        if(!empty($_SESSION['openid']) || !empty($_SESSION['member_id'])){
//            $member = M('member')->where(array('token'=>$_SESSION['token'],'openid'=>$_SESSION['openid']))->find();
//            $this->member = $member;
//            $this->member_id = $member['member_id'];
//            $this->openid = empty($member['openid'])?'':$member['openid'];
//        }
//        $this->assign('member', $member); //存储用户信息
//        if (!$this->member_id || !$this->openid) {
//            //重新获取微信openid 进行操作
//        }
//    }



    public function pay(){

        $distribution_config = M('distribution')->order('id desc')->limit(1)->find();
        $bili = $distribution_config['charge_rmb']/$distribution_config['charge_coin'];
        $money = I('money');
        $coin = I('coin');
        $complete = $money * $bili;
        if($coin != $complete){
            die(json_encode(array('state'=>0,'msg'=>'数据错误！')));
        }

        $wechat = M('wechat_config')->where(array('is_default'=>1))->find();

        $order = array(
            'charge_money'=>$money,
            'member_id'=>$_SESSION['member_id'],
            'openid'=>$_SESSION['openid'],
            'order_sn'=>getOrderSn(),
            'coin'=>$coin,
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
            'openid'=>$_SESSION['openid'],
            'member_id'=>$order['member_id'],
        );
        $sl = base64_encode(json_encode($ps));
//        $class_pay = new Weixin_accountClasslogic($wechat['id']);
        if($wechat['pay_type'] == 1)
            $class_pay = new Weixin_accountClasslogic($wechat['id']);
        else{
            $class_pay = new ThirdpayClasslogic();
            $class_pay->set_callback_url(SITE_URL . "/index.php?m=Mobile&c=Redpackets&a=recharge");
            $class_pay->set_notify_url(SITE_URL . '/payment/wxpay/third_notify.php');
            $order['attach'] = $wechat['id'] . "_".$order['order_id'];
        }
        $class_pay->set_order_data($order);
//        $pay_param = $class_pay->test_wechat_pay_build($_SESSION['openid']);
        $pay_param = $class_pay->wechat_pay_build();
        if(is_error($pay_param)){
            die(json_encode(array('state'=>0,'msg'=>$pay_param['message'])));
//            html_tips($pay_param['message']);
//            exit();
        }
        if($pay_param['status'] == 500){
            die(json_encode(array('state'=>0,'msg'=>$pay_param['msg'])));
        }
        $pay_param_str = base64_encode(json_encode($pay_param));
        $url = SITE_URL . "/payment/wxpay/pay.php?auth=".$pay_param_str."&ps=".$sl;
//        header("location: $url");
        die(json_encode(array('state'=>1,'url'=>$url)));

    }

    public function pay_return(){
        $param = $_GET['result_data'];
        F('pay_return_xml',$param);
        $result_data = @json_decode(base64_decode($param), true);
        if(!empty($result_data)){
            //跳转处理
            F('pay_return_arr',$result_data);
            $obj_class = new WxpayClasslogic();
            if($result_data['status'] == 0 && $result_data['result_code'] == 0){
                $obj_class->set_third_pay_success_data($result_data);
            }
            if($result_data['result_code'] == 'SUCCESS' && $result_data['return_code'] == 'SUCCESS'){
                $obj_class->set_pay_success_data($result_data);
            }
        }

    }


    public function pay_cancel(){

    }

    public function disanfang_pay(){
        $distribution_config = M('distribution')->order('id desc')->limit(1)->find();
        $bili = $distribution_config['charge_rmb']/$distribution_config['charge_coin'];
        $money = I('money');
        $coin = I('coin');
        $complete = $money * $bili;
        if($coin != $complete){
            die(json_encode(array('state'=>0,'msg'=>'数据错误！')));
        }

        $wechat = M('wechat_config')->where(array('is_default'=>1))->find();

        $order = array(
            'charge_money'=>0.02,
            'member_id'=>$_SESSION['member_id'],
            'openid'=>$_SESSION['openid'],
            'order_sn'=>getOrderSn(),
            'coin'=>$coin,
            'appid'=>$wechat['appid'],
            'charge_proportion'=>$bili,
            'status'=>0,
            'pay_code'=>'weixin',
            'createtime'=>time(),
        );
        $order['order_id'] = M('order')->add($order);



//        $pay_class = new PaymentClasslogic();
//        $result = $pay_class->fire_pay($order);
//        if($result['code'] == 10){
//            $qrcode = $this->fire_qrcode($result,$order['order_id']);
//            $detail = array('path'=>$qrcode,'order_id'=>$order['order_id']);
//            $pay_param_str = base64_encode(json_encode($detail));
//            $url = SITE_URL . U('Payment/fire_pay',array('others'=>$pay_param_str,'order_id'=>$order['order_id']));
//            die(json_encode(array('state'=>1,'url'=>$url)));
//        }else{
//            die(json_encode(array('state'=>0,'msg'=>$result['msg'])));
//        }


//        $url = $pay_class :: buyou_pay($order);
//        die(json_encode(array('state'=>1,'url'=>$url)));

//        $result = $pay_class::guofu_getUrl($order ,$order['order_id']);
//        if($result['respCode'] == '00'){
//            $qrcode = $this->guofu_pay($result,$order['order_id']);
//            $detail = array('path'=>$qrcode,'order_id'=>$order['order_id']);
//            $pay_param_str = base64_encode(json_encode($detail));
//            $url = SITE_URL . U('Payment/fire_pay',array('others'=>$pay_param_str,'order_id'=>$order['order_id']));
//            die(json_encode(array('state'=>1,'url'=>$url)));
//        }else{
//            die(json_encode(array('state'=>0,'msg'=>$result['respCode'])));
//        }

    }


    // 国富扫码支付
    private function guofu_pay($result ,$order_id){
        F('guo_res',$result);
        vendor("phpqrcode.phpqrcode");
        $str = get_rand_str(8,0,1);
        $field_name = $str . time() . '_pay.png';
        $fileName = "./Public/phpqrcode/Pay/";
        if(!is_dir($fileName)){
            mkdir($fileName,0777);
        }
        $data = $result['barCode'];
        $qrcode = new \QRcode();
        $qrcode_link = $fileName.$field_name;
        ob_clean();
        $qrcode::png($data,$qrcode_link,'L',5,4,false);

        $qrcode_path = ltrim($qrcode_link,'.');
        M('order')->where(array('order_id'=>$order_id))->save(array('pay_qrcode'=>SITE_URL . $qrcode_path));
        return SITE_URL . $qrcode_path;
    }

    //风支付
    private function fire_qrcode($result,$order_id)
    {
        $result['xiaoqiang']  = time();
        F('result',$result);
        vendor("phpqrcode.phpqrcode");
        $str = get_rand_str(8,0,1);
        $field_name = $str . time() . '_pay.png';
//        $fileName = "./Uploads/Pay/".date('Ym').'/';
//        if(!is_dir($fileName)){
//            mkdirs($fileName);
//        }
        $fileName = "./Public/phpqrcode/Pay/";
        if(!is_dir($fileName)){
            mkdir($fileName,0777);
        }

//        $data = explode('sign=', $result['url'])[1];
//        F('tt',$data);
        $qrcode = new \QRcode();
        $qrcode_link = $fileName.$field_name;
        ob_clean();
        $qrcode::png($result['url'],$qrcode_link,'L',5,4,false);

        $qrcode_path = ltrim($qrcode_link,'.');
        M('order')->where(array('order_id'=>$order_id))->save(array('pay_qrcode'=>SITE_URL . $qrcode_path));
        return SITE_URL . $qrcode_path;
    }

    public function fire_pay(){
        $info = I('others');
        $others = @json_decode(base64_decode($info),true);
        $order_id = I('order_id');
        if($order_id != $others['order_id']){
            html_tips('数据错误！',true,U('Redpackets/recharge'));
        }
        $order = M('order')->where(array('order_id'=>$order_id))->find();

        $this->assign('path' ,$others['path']);
        $this->assign('order_info' ,$order);
        $this->assign('back_url' ,SITE_URL . U('Mobile/notify/index'));
        $this->display();
    }

}
