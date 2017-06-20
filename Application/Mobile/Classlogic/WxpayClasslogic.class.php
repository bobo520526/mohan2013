<?php
/**
 * 微信支付类
 */
namespace Mobile\Classlogic;

use Think\Classlogic;
use Home\Classlogic\Weixin_accountClasslogic;
use Mobile\Classlogic\ThirdpayClasslogic;
set_time_limit(0);
ini_set('memory_limit','1024M');

class WxpayClasslogic extends Classlogic{
    private static $order = array(); // 订单真实数据
    private static $account_id = null;
    private static $pay_return_data = array();//支付返回数据信息
    private static $wechat_config = array();

    public static function set_order($order_id){
        if(is_array($order_id)) self::$order = $order_id;
        else self::$order = M('order')->where(array('order_id'=>$order_id));
    }

    public static function back_pay_url(){
//        $ps = array(
//            'appid'   =>self::$order['appid'],
//            'order_id'  =>self::$order['order_id'],
//            'openid'=>self::$order['openid'],
//            'member_id'=>self::$order['member_id'],
//        );
//        $sl = base64_encode(json_encode($ps));
//        $wechat = M('wechat_config')->where(array('is_default'=>1))->find();
//        $class_pay = new Weixin_accountClasslogic($wechat['id']);
//        $class_pay->set_order_data(self::$order);
////        $pay_param = $class_pay->test_wechat_pay_build(self::$order['openid']);
//        $pay_param = $class_pay->wechat_pay_build();
//        if(is_error($pay_param)){
//            die(json_encode(array('state'=>0,'msg'=>$pay_param['message'])));
////            html_tips($pay_param['message']);
////            exit();
//        }
//        $pay_param_str = base64_encode(json_encode($pay_param));
//        $url = SITE_URL . "/payment/wxpay/pay.php?auth=".$pay_param_str."&ps=".$sl;
    }

/**************************************** 订单支付前操作 end *****************************************************/

    /**
     * 支付成功返回设置信息
     * @param array $success_data
     */
    public static function set_pay_success_data($success_data = array()){
        if(empty($success_data)) return ;
        self::$pay_return_data = $success_data;
        if($success_data['result_code'] == 'SUCCESS' && $success_data['return_code'] == 'SUCCESS'){
            $attach = explode("_",$success_data['attach']);
            $account_id = $attach[0];
            if($account_id) self::$account_id = $account_id;
//            验证是否微信返回
            $check_sign = self :: check_callback_sign();
            if($check_sign){
                $order_id = $attach[1];
                if(intval($order_id)){
                    $order = M('order')->where(array('order_id'=>$order_id))->find();
                    self :: set_order($order);
                }
                self :: pay_order_success();

            }else{
                exit('fail');
            }
        }
    }
    public static function set_third_pay_success_data($success_data = array()){
        if(empty($success_data)) return ;
        self::$pay_return_data = $success_data;
        if($success_data['result_code'] == 0 && $success_data['status'] == 0){
            $attach = explode("_",$success_data['attach']);
            $account_id = $attach[0];
            if($account_id) self::$account_id = $account_id;
//            验证是否微信返回
            $check_sign = self :: check_callback_sign();
            if($check_sign){
                $order_id = $attach[1];
                if(intval($order_id)){
                    $order = M('order')->where(array('order_id'=>$order_id))->find();
                    self :: set_order($order);
                }
                self :: pay_order_success();

            }else{
                exit('fail');
            }
        }
    }

    /**
     * 验证回调签名 区分预付型代理商与账户
     * @return bool
     */
    private static function check_callback_sign(){
        self::$wechat_config = $wechat = M('wechat_config')->where(array('id'=>self::$account_id))->find();
        if($wechat['pay_type'] == 1){
            $handle_class = new Weixin_accountClasslogic(self::$account_id);
            $sign = $handle_class->MakeSign(self::$pay_return_data);
            if($sign == self::$pay_return_data['sign']) return true;
            else return false;
        }elseif($wechat['pay_type'] == 2){
            $handle_class = new ThirdpayClasslogic();
            return $handle_class->wechat_notify(self::$pay_return_data);
        }
    }

    private static function check_sign_true(){
        if(self::$wechat_config['pay_type'] == 1) {
            $return = array(
                'return_code' => 'SUCCESS',
                'return_msg' => 'OK'
            );
            $handle_class = new Weixin_accountClasslogic(self::$account_id);
            $xml = $handle_class->ToXml($return);
            exit($xml);
        }else{
            exit('fail');
        }
    }
/************************************************* 订单支付成功返回操作 ********************************************************************/

    /**
     * 订单支付成功操作
     */
    public static function pay_order_success(){
        self :: update_order_status();
    }


    /**
     * 更改订单状态
     */
    public static function update_order_status(){
        if(self::$order['status'] == 0 && !empty(self::$order)){
            $tag = array(
                'openid'=>self::$pay_return_data['openid'],
                'appid'=>self::$pay_return_data['appid'],
                'total_fee'=>self::$pay_return_data['total_fee']/100,
            );
            $order_update = array(
                'status'  =>  1,
                'order_paynum'  =>  self::$pay_return_data['transaction_id'],
                'tag'=>serialize($tag),
                'paytime'=>    time(),
            );
            $res = M('order')->where(array('order_id'=>self::$order['order_id']))->save($order_update);
            if($res){
                self::$order['status'] = $order_update['status'];
//                self :: update_order_pay_log_status();
                self :: order_charge();
                self :: check_sign_true();
            }
        }
    }

    /**
     * 充值成功，充值金币
     */
    private static function order_charge(){
        if(self::$order['status'] == 1){
            $member_coin = M('member')->where(array('member_id'=>self::$order['member_id']))->getField('coin');
            $coin = $member_coin + self::$order['coin'];
            M('member')->where(array('member_id'=>self::$order['member_id']))->save(array('coin'=>$coin));
        }
    }

    /**
     * 订单充值完成
     */
    private static function update_order_finsh(){
        $update = array(
            'order_status'      =>  90,
            'order_finsh_time'  =>  time(),
        );
        M('order')->where(array('order_id'=>self::$order['order_id']))->save($update);
    }
    /**
     * 更改订单支付日志状态
     */
    public static function update_order_pay_log_status(){
        $pay_tag = array(
            'transaction_id'    =>  self::$pay_return_data['transaction_id'],
            'openid'            =>  self::$pay_return_data['openid'],
            'total_fee'         =>  self::$pay_return_data['total_fee']/100,
            'trade_type'        =>  self::$pay_return_data['trade_type'],
            'attach'            =>  self::$pay_return_data['out_trade_no'],
            'appid'             =>  self::$pay_return_data['appid'],
            'mch_id'            =>  self::$pay_return_data['mch_id'],
        );
        $pay_log_update = array(
            'pay_after_status'  =>  1   ,
            'pay_tag'           =>  serialize($pay_tag),
            'pay_updatetime'    =>  time(),
        );
        M('pay_log')->where(array('pay_order_id'=>self::$order['order_id'],'pay_account_id'=>self::$order['order_account_id']))->save($pay_log_update);
    }




/************************************************* 订单支付成功返回操作 end ********************************************************************/


    /**
     * 返回信息和说明
     * @param string $msg
     * @param int $code
     * @return array
     */
    private static function return_back_msg($msg='',$code=0){
        die( array(
            'code'      =>  $code,
            'msg'       =>  $msg == ''? '数据异常,请稍后再操作':$msg
        ));
    }

}



