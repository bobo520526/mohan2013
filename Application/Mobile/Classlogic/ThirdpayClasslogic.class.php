<?php
/**
 * 第三方支付
 */
namespace Mobile\Classlogic;

use Think\Classlogic;
use Home\Classlogic\Weixin_accountClasslogic;
set_time_limit(0);
ini_set('memory_limit','1024M');

class ThirdpayClasslogic extends Classlogic{

//    private static $key = '18d299b337a1d717cb9327a8a2e364bc';
//    private static $mchid = '101510051915';

    private static $notify_url = null;
    private static $callback_url = null;
    private static $order = array();

    public static function set_callback_url($callback_url){
        if(empty($callback_url)) return false;
        self::$callback_url = $callback_url;
    }

    public static function set_notify_url($notify_url){
        if(empty($notify_url)) return false;
        self::$notify_url = $notify_url;
    }

    public static function set_order_data($order = array()){
        self::$order = $order;
    }

    public static function wechat_pay_build(){
        vendor("third.jsapi_request");
        $pay_class = new \Request();
        $pay_class->set_order_data(self::$order);
        $res = $pay_class->jsapi_submitOrderInfo(self::$notify_url,self::$callback_url);
//        F('Thirdpay',$res);
//        return $res;
        if($res['status'] == 0 && !empty($res['token_id']) && !empty($res['pay_info'])){
            $other_pay_info = json_decode($res['pay_info'],true);
            $pay_info = array(
                'appId'=>$other_pay_info['appId'],
                'timeStamp'=>$other_pay_info['timeStamp'],
                'nonceStr'=>$other_pay_info['nonceStr'],
                'package'=>$other_pay_info['package'],
                'signType'=>$other_pay_info['signType'],
                'paySign'=>$other_pay_info['paySign'],
                'status'=>0,
            );
            return $pay_info;
        }
        return $res;
    }

    public static function wechat_notify($return_data){
        vendor("third.jsapi_request");
        $pay_class = new \Request();
        $xml = self::ToXml($return_data);
        if($xml === false) return false;
        $res = $pay_class->callback($xml);
        F('callback',array('status'=>$res));
        return $res;
    }

    public static function ToXml($param) {
        if(!is_array($param) || count($param) <= 0) {
            return false;
        }

        $xml = "<xml>";
        foreach ($param as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

}



