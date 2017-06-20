<?php
/**
 * 微信支付类
 */
namespace Mobile\Classlogic;

use Think\Classlogic;
use Home\Classlogic\Weixin_accountClasslogic;
set_time_limit(0);
ini_set('memory_limit','1024M');

class PaymentClasslogic extends Classlogic{
    private static $order = array(); // 订单真实数据
    private static $config = array();


    public static function set_order($order_id){
        if(is_array($order_id)) self::$order = $order_id;
        else self::$order = M('order')->where(array('order_id'=>$order_id));
    }


    public function __construct()
    {
//        include(dirname(__FILE__).'/config.php');
//        $config['key'] = '57158b4c7691a58c62e039ddb48a5c81';
//        $config['partner'] = '23420';
//        self::$config = $config;

    }

    public static function buyou_pay($order_info)
    {
        $config['key'] = '57158b4c7691a58c62e039ddb48a5c81';
        $config['partner'] = '23420';

        $apiurl = "https://submit.buucard.com/PayBank.aspx";
        $partner = $config['partner'];
        $key = $config['key'];
        $ordernumber = $order_info['order_sn'];
        $banktype = 'WEIXINJSAPI';
        $paymoney = $order_info['charge_money'];
        $callbackurl = SITE_URL . U('Mobile/notify/buyou_notify');
        $hrefbackurl = '';
        $signSource = sprintf("partner=%s&banktype=%s&paymoney=%s&ordernumber=%s&callbackurl=%s%s", $partner, $banktype, $paymoney, $ordernumber, $callbackurl, $key);
        $sign = md5($signSource);
        $postUrl = $apiurl. "?banktype=".$banktype;
        $postUrl.="&partner=".$partner;
        $postUrl.="&paymoney=".$paymoney;
        $postUrl.="&ordernumber=".$ordernumber;
        $postUrl.="&callbackurl=".$callbackurl;
        $postUrl.="&hrefbackurl=".$hrefbackurl;
        $postUrl.="&sign=".$sign;
        return $postUrl;
//        header ("location:$postUrl");
    }

    public static function buyou_notify($data)
    {
        $partner = '23420';
        $key = '57158b4c7691a58c62e039ddb48a5c81';
        $orderstatus = $data['orderstatus'];
        $ordernumber = $data['ordernumber'];
        $paymoney = $data['paymoney'];
        $sign = $data['sign'];
        $signSource = sprintf("parent=%s&ordernumber=%s&orderstatus=%s&paymoney=%s%s", $partner, $ordernumber, $orderstatus, $paymoney, $key);
        if($sign == md5($signSource) && $orderstatus == 1){
            //file_put_contents('log.log', 'Success notify1  => '.serialize($data)."\r\n", FILE_APPEND);
            return true;
        }else{
//            file_put_contents('log.log', 'Success notify2  => '.serialize($data)."\r\n", FILE_APPEND);
            return false;
        }

    }


    public static function fire_pay($order_info)
    {
        $config['key'] = '0ee709d5999e43608664ab6ba35a1aff';
        $config['merchno'] = '80000137';

        $url = 'http://api.51firepay.com:20004/pay.ashx';
        $data['version'] = '2.0';
        $data['merchno'] = $config['merchno'];
        $data['paytype'] = '002';
        $data['organno'] = '';
        $data['remark'] = '';
        $data['callbackurl'] = '';
        $data['cpchannel'] = '';
        $data['proname'] = "订单【".$order_info['order_sn']."】支付";
        $data['ordno'] = $order_info['order_sn'];
        $data['price'] = $order_info['charge_money'] * 100;
        $data['notifyurl'] = SITE_URL . U('Mobile/notify/index');
        $key = $config['key'];
        ksort($data);
        $signOStr = '';
        foreach($data as $k=>$v){
            if($signOStr != ''){
                $signOStr .= '&';
            }
            $signOStr .= $k . '=' . $v;
        }
        $signOStr = $signOStr.'&'.$key;

        $data['sign'] = MD5($signOStr);

//        $rs = http_post($url, $data);
        $rs = httpRequest($url,'POST', $data);
        $rs = json_decode($rs, true);

        return $rs;
    }

    public static function fire_notify($data) {
       $order_info = M('order')->where(['order_sn'=>$data['ordno']])->find();
        if(!$order_info){
//            file_put_contents('log.log', 'Success notify  => '.serialize($data)."\r\n", FILE_APPEND);
            echo 'success';die();
        }
        return true;
        $config['key'] = '0ee709d5999e43608664ab6ba35a1aff';
        $config['merchno'] = '80000137';

        $arr = array(
            'price' => $data['price'],
            'proname' => $data['proname'],
            'merchno' => $data['merchno'],
            'ordno' => $data['ordno'],
            'paytype'=> $data['paytype'],
            'version' => $data['ver'],
            'tranid' => $data['tranid'],
            'notifyurl' => SITE_URL . U('Mobile/notify/index'),
        );

        $key = $config['key'];
        ksort($arr);
        $signOStr = '';
        foreach($arr as $k=>$v){
            if($signOStr != ''){
                $signOStr .= '&';
            }
            $signOStr .= $k . '=' . $v;
        }
        $signOStr = $signOStr.'&'.$key;
//        file_put_contents('log.log', 'Success1 notify  => '.serialize($signOStr)."\r\n", FILE_APPEND);
        $sign = MD5($signOStr);
        if($data['sign'] == $sign){
            return true;
        }else{
            return false;
        }
    }






    /*生成支付连接*/
    public static function guofu_getUrl($order_info,$other = array()){
        /**
         *  'ID'=>'001110148140001',
        'KEY'=>'888664AB728DE98C4A679A3CD77DAD24',
         */
        $notifyUrl = SITE_URL . U('Mobile/notify/guofu_notify');
        $traceno = $order_info['order_sn'];
        $amount = $order_info['charge_money'];
        //$traceno = time();
        /*$amount = 1;*/
        $url = 'http://api.gf-info.com/openPay';
        $url = 'http://api.emarfoo.com/passivePay';  //被扫
        $merchno = '001110148140001';
        $key = '888664AB728DE98C4A679A3CD77DAD24';

        $payType = '2';
        $data = [
            'amount' => $amount,
            'merchno' => $merchno,
            'notifyUrl' => $notifyUrl,
            'payType' => $payType,
            'returnUrl'=>SITE_URL . U('Mobile/Index/index'),
            'traceno' => $traceno,
        ];
        //dump($other);die();

        return $res = self::sendData($data,$key,$url);
    }

    //回调方法，把返回数据处理成统一规定返回
    public static function response($data)
    {
        $key = '888664AB728DE98C4A679A3CD77DAD24';
        $temp='';
        ksort($data);//对数组进行排序
        foreach ($data as $x=>$x_value){
            if ($x != 'signature'&& $x_value != null){
                $temp = $temp.$x."=".$x_value."&";
            }
        }
        $signature = strtoupper(MD5($temp.$key));
        if($signature != $data['signature']){
            return false;
        }else{
            return true;
        }
    }

    public static function gbkToUtf8($arr){
        if(is_array($arr)){
            $res = array();
            foreach($arr as $k => $v){
                $res[$k] = self::gbkToUtf8($v);
            }
            return $res;
        }else{
            return mb_convert_encoding ( $arr, "UTF-8", "GBK" );
        }
    }

    private static function utf8ToGbk($arr){
        if(is_array($arr)){
            $res = array();
            foreach($arr as $k => $v){
                $res[$k] = self::utf8ToGbk($v);
            }
            return $res;
        }else{
            return mb_convert_encoding ( $arr, "GBK" , "UTF-8" );
            // return iconv("UTF-8", "GBK//IGNORE", $arr);
        }
    }

    public static function sendData($post_data,$signature,$url){
        $temp='';
        ksort($post_data);//对数组进行排序
        // file_put_contents('./guofupay.txt',var_export($post_data,true),FILE_APPEND);
        //遍历数组进行字符串的拼接
        foreach ($post_data as $x=>$x_value){
            if ($x_value != null){
                $temp = $temp.$x."=".iconv('UTF-8','GBK//IGNORE',$x_value)."&";
            }
        }
        $md5=md5($temp.$signature);
        $reveiveData = $temp.'signature'.'='.$md5;
        //print  $reveiveData;
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $reveiveData);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        // var_dump($data);die;
        //return iconv('GB2312', 'UTF-8', $data);
        //显示获得的数据
        // echo $reveiveData;
        return json_decode(iconv('GBK//IGNORE', 'UTF-8', $data),true);
    }


}



