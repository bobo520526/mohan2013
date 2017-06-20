<?php
namespace Home\Classlogic;

use Think\Classlogic;

class WechatApiClasslogic extends Classlogic {
    private $id ;
    private $token = null;
    private $appid = null;
    private $appsecret = null;
    private $encodingaeskey = null;
    private $account_wechat = array();
    public function __construct($id){
        $this->id = $id;
        $this->account_wechat = M('wechat_config')->where(array('id'=>$this->id))->find();
        $this->token = $this->account_wechat['token'];
        $this->appid = $this->account_wechat['appid'];
        $this->appsecret = $this->account_wechat['appsecret'];
        $this->encodingaeskey = $this->account_wechat['wx_aeskey'];
    }

    /**
     * 验证签名
     * @return bool
     */
    public function checkSign() {
        $signkey = array($this->token, $_GET['timestamp'], $_GET['nonce']);
        sort($signkey, SORT_STRING);
        $signString = implode($signkey);
        $signString = sha1($signString);
        return $signString == $_GET['signature'];
    }


    public function parse(){
        $postStr = file_get_contents('php://input');
//        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $packet = array();
        if (!empty($postStr)){
            libxml_disable_entity_loader(true);
            $obj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
//            if($obj instanceof SimpleXMLElement) {
                $packet['openid'] = strval($obj->FromUserName);
                $packet['to'] = strval($obj->ToUserName);
                $packet['time'] = strval($obj->CreateTime);
                $packet['type'] = strval($obj->MsgType);
                $packet['event'] = strval($obj->Event);

                foreach ($obj as $variable => $property) {
                    $packet[strtolower($variable)] = (string)$property;
                }

                if($packet['type'] == 'event' && $packet['event'] == 'CLICK') {
                    $keyword = trim($obj->EventKey);
                    $packet['content'] = trim($obj->EventKey);
                }
                if($packet['type'] == 'text') {
                    $packet['content'] = strval($obj->Content);
                    $packet['redirection'] = false;
                    $packet['source'] = null;
                }
                if($packet['type'] == 'image') {
                    $packet['url'] = strval($obj->PicUrl);
                }
                if($packet['type'] == 'voice') {
                    $packet['media'] = strval($obj->MediaId);
                    $packet['format'] = strval($obj->Format);
                }
                if($packet['type'] == 'video') {
                    $packet['media'] = strval($obj->MediaId);
                    $packet['thumb'] = strval($obj->ThumbMediaId);
                }
                if($packet['type'] == 'shortvideo') {
                    $packet['media'] = strval($obj->MediaId);
                    $packet['thumb'] = strval($obj->ThumbMediaId);
                }
                if($packet['type'] == 'location') {
                    $packet['location_x'] = strval($obj->Location_X);
                    $packet['location_y'] = strval($obj->Location_Y);
                    $packet['scale'] = strval($obj->Scale);
                    $packet['label'] = strval($obj->Label);
                }
                if($packet['type'] == 'link') {
                    $packet['title'] = strval($obj->Title);
                    $packet['description'] = strval($obj->Description);
                    $packet['url'] = strval($obj->Url);
                }
                if($packet['event'] == 'subscribe') {
                    $scene = strval($obj->EventKey);
                    if(!empty($scene)) {
                        $packet['scene'] = str_replace('qrscene_', '', $scene);
                        $packet['ticket'] = strval($obj->Ticket);
                    }
                }
                if($packet['event'] == 'unsubscribe') {
                }
                if($packet['event'] == 'SCAN') {
                    $packet['type'] = 'qr';
                    $packet['scene'] = strval($obj->EventKey);
                    $packet['ticket'] = strval($obj->Ticket);
                }
                if($packet['event'] == 'LOCATION') {
                    $packet['type'] = 'trace';
                    $packet['location_x'] = strval($obj->Latitude);
                    $packet['location_y'] = strval($obj->Longitude);
                    $packet['precision'] = strval($obj->Precision);
                }
//                if(in_array($packet['event'], array('card_pass_check', 'card_not_pass_check', 'user_get_card', 'user_del_card', 'user_consume_card', 'poi_check_notify'))) {
//                    $this->analyzeCoupon($packet);
//                    exit();
//                }
                if($packet['event'] == 'merchant_order') {
                    $packet['type'] = 'merchant_order';
                    $packet['orderid'] = strval($obj->OrderId);
                    $packet['orderstatus'] = strval($obj->OrderStatus);
                    $packet['productid'] = strval($obj->ProductId);
                    $packet['skuinfo'] = strval($obj->SkuInfo);
                }
                if (in_array($packet['event'], array('pic_photo_or_album', 'pic_weixin', 'pic_sysphoto'))) {
                    $packet['sendpicsinfo'] = array();
                    $packet['sendpicsinfo']['count'] = strval($obj->SendPicsInfo->Count);
                    if (!empty($obj->SendPicsInfo->PicList)) {
                        foreach ($obj->SendPicsInfo->PicList->item as $item) {
                            if (!empty($item)) {
                                $packet['sendpicsinfo']['piclist'][] = strval($item->PicMd5Sum);
                            }
                        }
                    }
                }
                if (in_array($packet['event'], array('scancode_push', 'scancode_waitmsg'))) {
                    $packet['scancodeinfo'] = array();
                    $packet['scancodeinfo']['scanresult'] = strval($obj->ScanCodeInfo->ScanResult);
                    $packet['scancodeinfo']['scantype'] = strval($obj->ScanCodeInfo->ScanType);
                    $packet['scancodeinfo']['eventkey'] = strval($obj->ScanCodeInfo->EventKey);
                }

                if (in_array($packet['event'], array('location_select'))) {
                    $packet['sendlocationinfo'] = array();
                    $packet['sendlocationinfo']['location_x'] = strval($obj->SendLocationInfo->Location_X);
                    $packet['sendlocationinfo']['location_y'] = strval($obj->SendLocationInfo->Location_Y);
                    $packet['sendlocationinfo']['scale'] = strval($obj->SendLocationInfo->Scale);
                    $packet['sendlocationinfo']['label'] = strval($obj->SendLocationInfo->Label);
                    $packet['sendlocationinfo']['poiname'] = strval($obj->SendLocationInfo->Poiname);
                    $packet['sendlocationinfo']['eventkey'] = strval($obj->SendLocationInfo->EventKey);
                }
                if($packet['type'] == 'ENTER') {
                    $packet['type'] = 'enter';
                }
//            }
        }
        return $packet;
    }


}