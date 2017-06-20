<?php

namespace Home\Classlogic;

use Think\Classlogic;
use Think\Log;
class Weixin_accountClasslogic extends Classlogic {
    private $id = null;
    private $wechat_config = array();
    private $order = array();
    private $openid = null;
    private $amount;

    /**
     * 初始化操作
     */
    public function __construct($id){
        parent::__construct();
        $this->id = $id;
        $this->account_wechat();
    }
    public function account_wechat(){
        if($this->id){
            $this->wechat_config = M('wechat_config')->where(array('id'=>$this->id))->find();
            return $this->wechat_config;
        }
        return null;
    }
    /**
     * 验证微信签名 作为api对接时使用
     * @return bool
     */
    public function get_wechat_check_sign() {
        $token = $this->account['token'];
        $signkey = array($token, $_GET['timestamp'], $_GET['nonce']);
        sort($signkey, SORT_STRING);
        $signString = implode($signkey);
        $signString = sha1($signString);
        return $signString == $_GET['signature'];
    }

    public function get_wechat_appid(){
        return $this->wechat_config['appid'];
    }

    public function get_wechat_appsecret(){
        return $this->wechat_config['appsecret'];
    }

    /**
     * 获取此账户的微信公众号名称
     * @return mixed
     */
    public function get_wechat_name(){
        return $this->wechat_config['wx_name'];
    }

    /**
     * 获取微信公众号的二维码
     * @return string
     */
    public function get_wechat_qr_img(){
        return SITE_URL . $this->wechat_config['qr'];
    }

    public function get_wechat_share_ticket(){
        $share_ticket = unserialize($this->wechat_config['share_ticket']);
        if($share_ticket['expires_in'] > time())
            return $share_ticket['ticket'];
        return $this->getJsApiTicket();
    }
    /**
     * 获取微信用户资料
     * @param string $uniid openid
     * @param bool $isOpen
     * @return array|mixed|stdClass
     */
    public function fansQueryInfo($uniid, $isOpen = true) {
        if($isOpen) {
            $openid = $uniid;
        } else {
            exit('error');
        }
        $token = $this->get_access_token();
        if(is_error($token)){
            return $token;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$openid}&lang=zh_CN";
        $response = httpRequest($url,'GET');
        $result = @json_decode($response, true);
        if(empty($result)) {
            return error(-1, "接口调用失败");
        } elseif(!empty($result['errcode'])) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：".$this->wechat_error_code($result['errcode']));
        }
        return $result;
    }


    // 签名
    public function getSignPackage() {
        $jsapiTicket = $this->get_wechat_share_ticket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $this->get_wechat_appid(),
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "signature" => $signature,
            "url"       => $url,
            "rawString" => $string
        );
        return $signPackage;
    }
    // 随机字符串
    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }




    /**
     *获取access_token
     * @return mixed
     */
    public function get_access_token(){
        //判断是否过了缓存期
        $web_access_token = unserialize($this->wechat_config['web_access_token']);
        if($web_access_token['expire_time'] > time()){
            return $web_access_token['access_token'];
        }
        if(empty($this->wechat_config['appid']) && empty($this->wechat_config['appsecret'])) return null;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->wechat_config['appid']}&secret={$this->wechat_config['appsecret']}";
        $return = httpRequest($url,'GET');
        $return = json_decode($return,1);
        if($return['errcode']){
            return error(-1,$return['errcode'] . $this->wechat_error_code($return['errcode']));
        }
        $web_expires = time() + 7000; // 提前200秒过期
        $token = array(
            'expire_time'=>$web_expires,
            'access_token'=>$return['access_token']
        );
        M('wechat_config')->where(array('id'=>$this->wechat_config['id']))->save(array('web_access_token'=>serialize($token)));
        return $return['access_token'];
    }

    /**
     * 获取商户号
     * @return mixed
     */
    public function get_wechat_mchid(){
        return $this->wechat_config['mchid'];
    }
    /**
     * 获取商户支付密匙
     * @return mixed
     */
    public function get_wechat_mchid_key(){
        return $this->wechat_config['mchkey'];
    }

    /**
     * 获取商户证书 cret
     * @return mixed
     */
    public function get_wechat_apiclient_cert(){
        return  $this->wechat_config['apiclient_cert'];
    }

    /**
     * 获取商户证书 key
     * @return mixed
     */
    public function get_wechat_apiclient_key(){
        return $this->wechat_config['apiclient_key'];
    }

    public function get_wechat_rootca(){
        return  $this->wechat_config['rootca_pem'];
    }
    /**
     * 创建自定义菜单
     * @return mixed
     */
    public function create_wechat_menu(){
        //获取父级菜单
        $p_menus = M('wechat_menu')->where(array('token'=>$this->wechat_config['token'],'pid'=>0))->order('id ASC')->select();
        $p_menus = convert_arr_key($p_menus,'id');
        $menu_json = $this->convert_menu($p_menus,$this->account_id);

        // http post请求
        if(!count($p_menus) > 0){
            $this->die_back('没有菜单可发布',0,U('Wechat/menu'));
        }
        $access_token = $this->get_access_token();
        if(!$access_token){
            $this->die_back('获取access_token失败',0,U('Wechat/menu'));
        }
        $url ="https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
        return httpRequest($url,'POST',$menu_json);
    }

    /**
     * 菜单转换微信格式
     * @param $p_menus
     * @param $account_id
     * @return string
     */
    private function convert_menu($p_menus,$account_id){
        $key_map = array(
            'scancode_waitmsg'=>'rselfmenu_0_0',
            'scancode_push'=>'rselfmenu_0_1',
            'pic_sysphoto'=>'rselfmenu_1_0',
            'pic_photo_or_album'=>'rselfmenu_1_1',
            'pic_weixin'=>'rselfmenu_1_2',
            'location_select'=>'rselfmenu_2_0',
        );
        $new_arr = array();
        $count = 0;
        foreach($p_menus as $k => $v){
            $new_arr[$count]['name'] = $v['name'];
            //获取子菜单
            $c_menus = M('wx_menu')->where(array('account_id'=>$account_id,'pid'=>$k))->select();
            if($c_menus){
                foreach($c_menus as $kk=>$vv){
                    $add = array();
                    $add['name'] = $vv['name'];
                    $add['type'] = $vv['type'];
                    // click类型
                    if($add['type'] == 'click'){
                        $add['key'] = $vv['value'];
                    }elseif($add['type'] == 'view'){
                        $add['url'] = $vv['value'];
                    }else{
                        //$add['key'] = $key_map[$add['type']];
                        $add['key'] = $vv['value'];
                    }
                    $add['sub_button'] = array();
                    if($add['name']){
                        $new_arr[$count]['sub_button'][] = $add;
                    }
                }
            }else{
                $new_arr[$count]['type'] = $v['type'];
                // click类型
                if($new_arr[$count]['type'] == 'click'){
                    $new_arr[$count]['key'] = $v['value'];
                }elseif($new_arr[$count]['type'] == 'view'){
                    //跳转URL类型
                    $new_arr[$count]['url'] = $v['value'];
                }else{
                    //其他事件类型
                    //$new_arr[$count]['key'] = $key_map[$v['type']];
                    $new_arr[$count]['key'] = $v['value'];
                }
            }
            $count++;
        }
        return json_encode(array('button'=>$new_arr),JSON_UNESCAPED_UNICODE);
    }

    /**
     * 微信错误码信息
     * @param $code
     * @return string
     */
    public function wechat_error_code($code) {
        $errors = array(
            '-1' => '系统繁忙',
            '0' => '请求成功',
            '40001' => '获取access_token时AppSecret错误，或者access_token无效',
            '40002' => '不合法的凭证类型',
            '40003' => '不合法的OpenID',
            '40004' => '不合法的媒体文件类型',
            '40005' => '不合法的文件类型',
            '40006' => '不合法的文件大小',
            '40007' => '不合法的媒体文件id',
            '40008' => '不合法的消息类型',
            '40009' => '不合法的图片文件大小',
            '40010' => '不合法的语音文件大小',
            '40011' => '不合法的视频文件大小',
            '40012' => '不合法的缩略图文件大小',
            '40013' => '不合法的APPID',
            '40014' => '不合法的access_token',
            '40015' => '不合法的菜单类型',
            '40016' => '不合法的按钮个数',
            '40017' => '不合法的按钮个数',
            '40018' => '不合法的按钮名字长度',
            '40019' => '不合法的按钮KEY长度',
            '40020' => '不合法的按钮URL长度',
            '40021' => '不合法的菜单版本号',
            '40022' => '不合法的子菜单级数',
            '40023' => '不合法的子菜单按钮个数',
            '40024' => '不合法的子菜单按钮类型',
            '40025' => '不合法的子菜单按钮名字长度',
            '40026' => '不合法的子菜单按钮KEY长度',
            '40027' => '不合法的子菜单按钮URL长度',
            '40028' => '不合法的自定义菜单使用用户',
            '40029' => '不合法的oauth_code',
            '40030' => '不合法的refresh_token',
            '40031' => '不合法的openid列表',
            '40032' => '不合法的openid列表长度',
            '40033' => '不合法的请求字符，不能包含\uxxxx格式的字符',
            '40035' => '不合法的参数',
            '40038' => '不合法的请求格式',
            '40039' => '不合法的URL长度',
            '40050' => '不合法的分组id',
            '40051' => '分组名字不合法',
            '41001' => '缺少access_token参数',
            '41002' => '缺少appid参数',
            '41003' => '缺少refresh_token参数',
            '41004' => '缺少secret参数',
            '41005' => '缺少多媒体文件数据',
            '41006' => '缺少media_id参数',
            '41007' => '缺少子菜单数据',
            '41008' => '缺少oauth code',
            '41009' => '缺少openid',
            '42001' => 'access_token超时',
            '42002' => 'refresh_token超时',
            '42003' => 'oauth_code超时',
            '43001' => '需要GET请求',
            '43002' => '需要POST请求',
            '43003' => '需要HTTPS请求',
            '43004' => '需要接收者关注',
            '43005' => '需要好友关系',
            '44001' => '多媒体文件为空',
            '44002' => 'POST的数据包为空',
            '44003' => '图文消息内容为空',
            '44004' => '文本消息内容为空',
            '45001' => '多媒体文件大小超过限制',
            '45002' => '消息内容超过限制',
            '45003' => '标题字段超过限制',
            '45004' => '描述字段超过限制',
            '45005' => '链接字段超过限制',
            '45006' => '图片链接字段超过限制',
            '45007' => '语音播放时间超过限制',
            '45008' => '图文消息超过限制',
            '45009' => '接口调用超过限制',
            '45010' => '创建菜单个数超过限制',
            '45015' => '回复时间超过限制',
            '45016' => '系统分组，不允许修改',
            '45017' => '分组名字过长',
            '45018' => '分组数量超过上限',
            '46001' => '不存在媒体数据',
            '46002' => '不存在的菜单版本',
            '46003' => '不存在的菜单数据',
            '46004' => '不存在的用户',
            '47001' => '解析JSON/XML内容错误',
            '48001' => 'api功能未授权',
            '50001' => '用户未授权该api',
            '40070' => '基本信息baseinfo中填写的库存信息SKU不合法。',
            '41011' => '必填字段不完整或不合法，参考相应接口。',
            '40056' => '无效code，请确认code长度在20个字符以内，且处于非异常状态（转赠、删除）。',
            '43009' => '无自定义SN权限，请参考开发者必读中的流程开通权限。',
            '43010' => '无储值权限,请参考开发者必读中的流程开通权限。',
            '43011' => '无积分权限,请参考开发者必读中的流程开通权限。',
            '40078' => '无效卡券，未通过审核，已被置为失效。',
            '40079' => '基本信息base_info中填写的date_info不合法或核销卡券未到生效时间。',
            '45021' => '文本字段超过长度限制，请参考相应字段说明。',
            '40080' => '卡券扩展信息cardext不合法。',
            '40097' => '基本信息base_info中填写的url_name_type或promotion_url_name_type不合法。',
            '49004' => '签名错误。',
            '43012' => '无自定义cell跳转外链权限，请参考开发者必读中的申请流程开通权限。',
            '40099' => '该code已被核销。'
        );
        $code = strval($code);
        if($code == '40001' || $code == '42001') {
            M('wx_user')->where(array('account_id'=>$this->wechat_config['account_id']))->save(array('web_access_token'=>'','web_expires'=>0));
            return '微信公众平台授权异常, 系统已修复这个错误, 请刷新页面重试.';
        }
        if($errors[$code]) {
            return $errors[$code];
        } else {
            return '未知错误';
        }
    }

    /**
     * 把数组转换成 xml
     * 输出xml字符
     **/
    public function ToXml($param) {
        if(!is_array($param) || count($param) <= 0) {
            return error(-1,"数组数据异常！");
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

    /**
     * * 将xml转为array
     * @param $xml
     * @return array|mixed
     */
    public function FromXml($xml){
        if(!$xml){
            return error(-1,"xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $array = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array;
    }

    /**
     * 格式化参数格式化成url参数
     * @param $param
     * @return string
     */
    public function ToUrlParams($param){
        $buff = "";
        foreach ($param as $k => $v) {
            if (empty($v)) continue;
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function MakeSign($param){
        //签名步骤一：按字典序排序参数
        ksort($param,SORT_STRING);
        $string = $this->ToUrlParams($param);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$this->get_wechat_mchid_key();
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }


    //微信支付开始

    public function set_order_data($order_id){
        $order_item = array();
        //处理订单信息
        if(is_array($order_id)){
            $order_item = $order_id;
        }else{
            $order_item = array();
        }
        if(empty($order_item)){
            return false;
        }
        $this->order = $order_item;
        return true;
    }

    /**
     * 微信 JSAPI 构建支付
     * @return array
     */
    public function wechat_pay_build(){
        $wOpt = $package = array();
        $package['appid'] = $this->get_wechat_appid();
        $package['mch_id'] = $this->get_wechat_mchid();
        $package['nonce_str'] = $this->createNonceStr(8);
        $package['body'] = '订单【'.$this->order['order_sn'].'】';
        $package['attach'] = $this->id . "_".$this->order['order_id'];
        $package['out_trade_no'] = $this->order['order_sn'];
        $package['total_fee'] = $this->order['charge_money'] * 100;
        $package['spbill_create_ip'] = get_client_ip();
        $package['time_start'] = date('YmdHis', time());
        $package['time_expire'] = date('YmdHis', time() + 6000);
        $package['notify_url'] = SITE_URL . '/payment/wxpay/notify.php';
        $package['trade_type'] = 'JSAPI';
        $package['openid'] = $this->order['openid'];
        $package['sign'] = $this->MakeSign($package);
        $dat = $this->ToXml($package);
        $return = httpRequest('https://api.mch.weixin.qq.com/pay/unifiedorder','POST', $dat);

        $response = $this->FromXml($return);
        if(strval($response['return_code']) == 'FAIL'){
            return error(-1, strval($response['return_msg']));
        }
        if(strval($response['result_code']) == 'FAIL'){
            return error(-1, strval($response['err_code']).': '.strval($response['err_code_des']));
        }
        $prepayid = $response['prepay_id'];
        $wOpt['appId'] = $this->get_wechat_appid();
        $wOpt['timeStamp'] = time();
        $wOpt['nonceStr'] = $this->createNonceStr(8);
        $wOpt['package'] = 'prepay_id='.$prepayid;
        $wOpt['signType'] = 'MD5';
        $wOpt['paySign'] = $this->MakeSign($wOpt);
        return $wOpt;
    }
    public function test_wechat_pay_build($openid){
        $wOpt = $package = array();
        $package['appid'] = $this->get_wechat_appid();
        $package['mch_id'] = $this->get_wechat_mchid();
        $package['nonce_str'] = $this->createNonceStr(8);
        $package['body'] = '测试支付';
        $package['attach'] = 1;
        $package['out_trade_no'] = $this->createNonceStr(12);
        $package['total_fee'] = 1;
        $package['spbill_create_ip'] = get_client_ip();
        $package['time_start'] = date('YmdHis', time());
        $package['time_expire'] = date('YmdHis', time() + 6000);
        $package['notify_url'] = SITE_URL . '/payment/wxpay/notify.php';
        $package['trade_type'] = 'JSAPI';
        $package['openid'] = $openid;
        $package['sign'] = $this->MakeSign($package);
        $dat = $this->ToXml($package);
        $return = httpRequest('https://api.mch.weixin.qq.com/pay/unifiedorder','POST', $dat);

        $response = $this->FromXml($return);
        if(strval($response['return_code']) == 'FAIL'){
            return error(-1, strval($response['return_msg']));
        }
        if(strval($response['result_code']) == 'FAIL'){
            return error(-1, strval($response['err_code']).': '.strval($response['err_code_des']));
        }
        $prepayid = $response['prepay_id'];
        $wOpt['appId'] = $this->get_wechat_appid();
        $wOpt['timeStamp'] = time();
        $wOpt['nonceStr'] = $this->createNonceStr(8);
        $wOpt['package'] = 'prepay_id='.$prepayid;
        $wOpt['signType'] = 'MD5';
        $wOpt['paySign'] = $this->MakeSign($wOpt);
        return $wOpt;
    }


/**********************企业付款操作***********************************/
    /**
     * 	作用：使用证书，以post方式提交xml到对应的接口url
     */
    private function post_xml_SSLCurl($xml,$url,$second=30){
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch,CURLOPT_HEADER,FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
//		curl_setopt($ch,CURLOPT_CAINFO, WxPayConf_pub::SSLROOTCA_PATH);
        curl_setopt($ch,CURLOPT_CAINFO,  dirname(dirname(dirname(dirname(__FILE__))))  . "/Uploads/" . $this->get_wechat_rootca());
        //使用证书：cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
//		curl_setopt($ch,CURLOPT_SSLCERT, WxPayConf_pub::SSLCERT_PATH);
        curl_setopt($ch,CURLOPT_SSLCERT, dirname(dirname(dirname(dirname(__FILE__))))  . "/Uploads/" . $this->get_wechat_apiclient_cert());
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
//		curl_setopt($ch,CURLOPT_SSLKEY, WxPayConf_pub::SSLKEY_PATH);
        curl_setopt($ch,CURLOPT_SSLKEY, dirname(dirname(dirname(dirname(__FILE__))))  . "/Uploads/" . $this->get_wechat_apiclient_key());

        //post提交方式
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        }else {
            $error = curl_errno($ch);
            return error(-1,"curl出错，错误码:$error");
        }
    }

    /**
     * 发红包参数
     * @return array|string
     */
    public function transfers(){
//        $money = rand(1,100)/100;
        $money = $this->amount;
        $parameters["nonce_str"]    = $this->createNoncestr();//随机字符串
        $parameters['mch_billno']   = $this->get_wechat_mchid().date('Ymd').rand(1000000000, 9999999999);
        $parameters["mch_id"]       = $this->get_wechat_mchid();//商户号
        $parameters['wxappid']      = $this->get_wechat_appid();//公众账号ID
        $parameters['nick_name']    = $this->wechat_config['wx_name'];
        $parameters['send_name']    = $this->wechat_config['wx_name'];
        $parameters['re_openid']    = $this->openid;//发给谁
        $parameters['total_amount'] = $money * 100;//付款金额
        $parameters['min_value']    = $money * 100;//最小红包
        $parameters['max_value']    = $money * 100;//最大红包
        $parameters['total_num']    = '1';//最大红包
        $parameters['wishing']      = '感谢您关注'.$this->wechat_config['wx_name'].'!';//最大红包
        $parameters['client_ip']    = $_SERVER['REMOTE_ADDR'];//最大红包
        $parameters['act_name']     = '感谢您关注'.$this->wechat_config['wx_name'];//最大红包
        $parameters['remark']       = '感谢您关注'.$this->wechat_config['wx_name'] .'!';//最大红包
        $parameters["sign"]         = $this->MakeSign($parameters);//签名
        return $this->ToXml($parameters);
    }

    /**
     * 设置openid
     * @param $openid
     */
    public function set_openid($openid){
        $this->openid = $openid;
    }

    //设置支付金额
    public function set_amount($amount){
        $this->amount = $amount * 100;
    }
    /**
     * 发红包
     * @return array|mixed
     */
    public function get_transfers_result(){
        $xml = $this->transfers();
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        $result = $this->post_xml_SSLCurl($xml,$url);
        $response = $this->FromXml($result);
        return $response;
    }

    /**
     * 企业付款
     * @return array|string
     */
    public function mmpaymkttransfers(){
        $parameters["nonce_str"]    = $this->createNoncestr();
        $parameters['partner_trade_no']   = $this->get_wechat_mchid().date('Ymd').rand(1000000000, 9999999999);
        $parameters["mchid"]       = $this->get_wechat_mchid();
        $parameters['mch_appid']      = $this->get_wechat_appid();
        $parameters['check_name']      = 'NO_CHECK';
        $parameters['openid']      = $this->openid;
        $parameters['amount']      = $this->amount;
        $parameters['desc']      = '用户提现';
        $parameters['spbill_create_ip']      = get_client_ip();
        $parameters["sign"]         = $this->MakeSign($parameters);//签名
        return $this->ToXml($parameters);
    }

    /**
     * 立即返现型操作
     * @return array|mixed
     */
    public function get_pay_withdrawal(){
        $xml = $this->mmpaymkttransfers();
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
        $result = $this->post_xml_SSLCurl($xml,$url);
        $response = $this->FromXml($result);
        return $response;
    }


/******************************************企业付款操作end*********************************************/


/************************************微信支付 ( 二维码操作 ) start *****************************************************/
    //模式一
    /**
     * 流程：
     * 1、组装包含支付信息的url，生成二维码
     * 2、用户扫描二维码，进行支付
     * 3、确定支付之后，微信服务器会回调预先配置的回调地址，在【微信开放平台-微信支付-支付配置】中进行配置
     * 4、在接到回调通知之后，用户进行统一下单支付，并返回支付信息以完成支付（见：native_notify.php）
     * 5、支付完成之后，微信服务器会通知支付成功
     * 6、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
     * @return string
     */
    public function pay_wechat_qr_mode_one(){
        $param["appid"]                =   $this->get_wechat_appid();
        $param['mch_id']               =   $this->get_wechat_mchid();
        $param["time_stamp"]           =   time();
        $param['nonce_str']            =   $this->createNoncestr();
        $param['product_id']           =   $this->order['order_package_id'];
//        $sign                          =   $this->MakeSign($param);
        $param['sign']                 =   $this->MakeSign($param);

        $buff = "";
        foreach ($param as $k => $v) {
            $buff .= $k . "=" . $v . "&";
        }

        $buff = trim($buff, "&");
//        $qr_url = "weixin://wxpay/bizpayurl?sign=" .$sign ."&" . $this->ToUrlParams($param);
        $qr_url = "weixin://wxpay/bizpayurl?" .$buff;
        return $qr_url;
        //生成二维码
    }
    //模式二
    /**
     * 流程：
     * 1、调用统一下单，取得code_url，生成二维码
     * 2、用户扫描二维码，进行支付
     * 3、支付完成之后，微信服务器会通知支付成功
     * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
     */

    public function set_agent_order_data($agent_data){

    }

    public function pay_wechat_qr_mode_two($data){
        $package = array();
        $package['appid'] = $this->get_wechat_appid();
        $package['mch_id'] = $this->get_wechat_mchid();
        $package['nonce_str'] = $this->createNonceStr(8);
        $package['body'] = $data['title'];
        // 此处需要区分终端用户订单与代理商批量充值
        $package['attach'] = $this->id . "_".$data['attach'];
        $package['out_trade_no'] = $this->createNonceStr(8);
        $package['product_id'] = $data['order_package_id'];
        $package['total_fee'] = $data['order_amount'] * 100;
        $package['spbill_create_ip'] = get_client_ip();
        $package['time_start'] = date('YmdHis', time());
        $package['time_expire'] = date('YmdHis', time() + 6000);
        $package['notify_url'] = SITE_URL . '/payment/wxpay/notify.php';
        $package['trade_type'] = 'NATIVE';
//        $package['openid'] = $this->order['order_buyer_openid'];
        $package['sign'] = $this->MakeSign($package);
        $dat = $this->ToXml($package);
        $return = httpRequest('https://api.mch.weixin.qq.com/pay/unifiedorder','POST', $dat);

        $response = $this->FromXml($return);
        if(strval($response['return_code']) == 'FAIL'){
            return error(-1, strval($response['return_msg']));
        }
        if(strval($response['result_code']) == 'FAIL'){
            return error(-1, strval($response['err_code']).': '.strval($response['err_code_des']));
        }
        return $response;
    }



/************************************微信支付 ( 二维码操作 ) end *****************************************************/
    public static function logging($level = 'info', $message = '', $filename = 'access') {
        $filename = C('LOG_PATH').$filename.'_' . date('Ymd') . '.log';
        mkdirs(dirname($filename));
        $content = date('Y-m-d H:i:s') . " {$level} :\n------------\n";
        if(is_string($message) || is_numeric($message)) {
            $content .= "String:\n{$message}\n";
        }
        if(is_array($message)) {
            $content .= "Array:\n";
            foreach($message as $key => $value) {
                $content .= sprintf("%s : %s ;\n", $key, $value);
            }
        }
        if($message == 'get') {
            $content .= "GET:\n";
            foreach($_GET as $key => $value) {
                $content .= sprintf("%s : %s ;\n", $key, $value);
            }
        }
        if($message == 'post') {
            $content .= "POST:\n";
            foreach($_POST as $key => $value) {
                $content .= sprintf("%s : %s ;\n", $key, $value);
            }
        }
        $content .= "\n";

        $fp = fopen($filename, 'a+');
        fwrite($fp, $content);
        fclose($fp);
    }

    /******微信授权中心***/

    //微信授权处理
    public function wechat_oauth_jump($back_url,$scope_type=''){
        $redirect_uri = urlencode($back_url);
        $state = 'wechat';
        $scope = $scope_type == '' ? 'snsapi_userinfo':'snsapi_base';
        $oauth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->get_wechat_appid() . '&redirect_uri=' . $redirect_uri . '&response_type=code&scope=' . $scope . '&state=' . $state . '#wechat_redirect';
        header('Expires: 0');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cahe, must-revalidate');
        header('Cache-Control: post-chedk=0, pre-check=0', false);
        header('Pragma: no-cache');
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: $oauth_url");
    }

    public function get_oauth_userInfo($code){
        $access_token_array = $this->get_oauth_access_token($code);
        $access_token = $access_token_array['access_token'];
        $openid = $access_token_array['openid'];
//        $userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
//        $userinfo_json = httpRequest($userinfo_url,'GET');
//        $userinfo_array = json_decode($userinfo_json, true);
//        //获取
//        $fans = $this->fansQueryInfo($openid);
//        $userinfo_array['subscribe'] = $fans['subscribe'];
        $userinfo_array = array(
            'openid'    =>$openid
        );
        if(!empty($openid)) $userinfo_array['games'] = true;
        return $userinfo_array;
    }

    private function get_oauth_access_token($code){
        $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->get_wechat_appid()."&secret=".$this->get_wechat_appsecret()."&code=".$code."&grant_type=authorization_code";
        $access_token_json = httpRequest($access_token_url,'GET');
        $access_token_array = json_decode($access_token_json, true);
        return $access_token_array;
    }

    /****授权结束***/



    /*** jssdk **/

    // 签名
    public function get_jssdk_sign_package($url = null) {
        $jsapiTicket = $this->get_wechat_share_ticket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        if($url === null){
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId"     => $this->get_wechat_appid(),
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "rawString" => $string,
            "signature" => $signature
        );
        return $signPackage;
    }

    /**
     * 根据 access_token 获取 icket
     * @return type
     */
    public function getJsApiTicket(){
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
        $return = httpRequest($url,'GET');
        $return = json_decode($return,1);
        if($return['errcode'] || $return['errmsg'] != 'ok'){
            return error(-1,$return['errcode'] . $this->wechat_error_code($return['errcode']));
        }
        $array = array(
            'ticket'    =>  $return['ticket'],
            'expires_in'=>  time() + $return['expires_in'] - 200, // 提前200秒过期
        );
        M('wechat_config')->where(array('id'=>$this->wechat_config['id']))->save(array('share_ticket'=>serialize($array)));
        return $return['ticket'];
    }



}

