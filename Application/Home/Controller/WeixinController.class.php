<?php

namespace Home\Controller;
use Think\Controller;
use Home\Classlogic\WechatApiClasslogic;
use Home\Classlogic\Weixin_accountClasslogic;
class WeixinController extends Controller {
    public $client;
    public $handle;
    public $message;
    public $id = null;
    public $wechat_config = array();
    public function __construct(){
        parent::__construct();
        $this->id = intval($_GET['id']);
        if($this->id){
            //获取微信配置信息
            $info = M('wechat_config')->where(array('id'=>$this->id))->find();
           if($info){
               $this->wechat_config = $info;
           }
        }
        if(empty($this->wechat_config)) $this->valid();
        $this->handle = new WechatApiClasslogic($this->id);
    }


    private function valid(){
        ob_clean();
        exit($_GET['echostr']);
    }

    public function index(){
        if(empty($this->wechat_config)){
            $this->valid();
        }
        else
            $this->startProcessing();
    }

    /**
     * 开始处理微信用户信息
     */
    public function startProcessing(){
        if(!$this->id) exit("Miss Params");
        if(empty($this->wechat_config)) exit("Miss Account Wechat");
        if(!$this->handle->checkSign()) exit('Check Sign Fail.');
        if(strtolower($_SERVER['REQUEST_METHOD']) == 'get') {
            $this->valid();
        }
        if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $message = $this->handle->parse();
            $this->message = $message;
            $this->memberRecord($message);
            $this->responseCustomMsg();
        }
    }

    //分析
    public function analysis(){

    }

    /**
     * 系统存储用户信息
     * @param $message
     */
    private function memberRecord($message){
        $model_member = M('member');
        $member = $model_member->where(array('openid'=>$message['openid'],'token'=>$this->wechat_config['token']))->find();
        if(!empty($member)) {
            $rec = array();
            if (!empty($member['subscribe'])) {
                if ($message['event'] == 'unsubscribe') {
                    $rec['subscribe'] = 0;
                    $rec['subscribe_time'] = 0;
                    $rec['unsubscribe_time'] = $message['time'];
                }
            } else {
                if ($message['event'] != 'unsubscribe' && $message['event'] != 'ShakearoundUserShake') {
                    $rec['subscribe'] = 1;
                    $rec['subscribe_time'] = $message['time'];
                    $rec['unsubscribe_time'] = 0;
                    $rec['oauth'] = 'weixin';
                }
            }
            //加入公共ID
            if(empty($member['unionid']) && !empty($message['unionid']))
                $rec['unionid'] = $message['unionid'];

            if(!empty($rec)){
                if ($message['event'] == 'location') {
                    $rec['location'] = base64_encode(serialize($message));
                }
                $model_member->where(array('openid'=>$message['openid'],'member_id'=>$member['member_id']))->save($rec);
            }
        } else {
            $rec = array();
            $rec['token'] = $this->wechat_config['token'];
            $rec['openid'] = $message['openid'];
            //加入公共ID
            if(!empty($message['unionid']))
                $rec['unionid'] = $message['unionid'];
            if ($message['event'] == 'unsubscribe') {
                $rec['subscribe'] = 0;
                $rec['subscribe_time'] = 0;
                $rec['unsubscribe_time'] = $message['time'];
            } else {
                $rec['subscribe'] = 1;
                $rec['subscribe_time'] = $message['time'];
                $rec['unsubscribe_time'] = 0;
            }

            if ($message['event'] == 'location') {
                $rec['location'] = base64_encode(serialize($message));
            }

            //获取用户微信基本信息
            vendor("phpemoji.emoji");
            $obj_class = new Weixin_accountClasslogic($this->id);
            $base_fans = $obj_class->fansQueryInfo($message['openid']);
            if($base_fans['nickname']) $rec['nickname'] = emoji_unified_to_html($base_fans['nickname']);
            if($base_fans['sex']) $rec['sex'] = $base_fans['sex'];
            if($base_fans['city']) $rec['city'] = $base_fans['city'];
            if($base_fans['province']) $rec['province'] = $base_fans['province'];
            if($base_fans['country']) $rec['country'] = $base_fans['country'];
            if($base_fans['headimgurl']) $rec['head_pic'] = $base_fans['headimgurl'];
            if($model_member->where(array('openid'=>$rec['openid'],'token'=>$rec['token']))->find()){
                $model_member->where(array('openid'=>$message['openid'],'member_id'=>$member['member_id']))->save($rec);
            }else{
                $rec['createtime'] = time();
                $model_member->add($rec);
            }
        }
    }

    private function responseCustomMsg(){
        if($this->message['event'] == 'subscribe'){
            $this->subscribe();
        }
        if($this->message['content']){
//            $where['token'] = $this->wechat_config['token'];
//            $where['keyword'] = $this->message['content'];
//            $keyword = M('keyword')->where($where)->find();
//            $type = null;
//            if(!empty($keyword)){
//                $type = $keyword['type'];
//            }
            $this->msg();
        }
        $this->others();
    }

    /**
     * 关注处理
     */
    private function subscribe($msg = ''){
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";
        $contentStr = $msg == ''?"欢迎关注【" . $this->wechat_config['wx_name']."】":$msg;
        $resultStr = sprintf($textTpl, $this->message['openid'], $this->message['to'], $this->message['time'], 'text', $contentStr);
        ob_clean();
        exit($resultStr);
    }

    /**
     * 被动回复消息
     * @param $type
     * @param $pid
     */
    private function msg(){
        $where['keyword'] = $this->message['content'];
        $keyword = M('keyword')->where($where)->find();
        if(empty($keyword)){
            $this->subscribe("系统未找到与".$this->message['content']."相关内容");
        }
        if(!empty($keyword)){
            $resultStr = "";
//            $where['token'] = $this->wechat_config['token'];
            if($keyword['type'] == 'text') {
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>";
                $contentStr = $keyword['content'];
                $resultStr = sprintf($textTpl, $this->message['openid'], $this->message['to'], $this->message['time'], 'text', $contentStr);
                ob_clean();
                exit($resultStr);
            }
            if($keyword['type'] == 'img') {
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <ArticleCount><![CDATA[%s]]></ArticleCount>
                            <Articles>
                                <item>
                                    <Title><![CDATA[%s]]></Title>
                                    <Description><![CDATA[%s]]></Description>
                                    <PicUrl><![CDATA[%s]]></PicUrl>
                                    <Url><![CDATA[%s]]></Url>
                                </item>
                            </Articles>
                            </xml>";
                $resultStr = sprintf($textTpl,$this->message['openid'], $this->message['to'], $this->message['time'],'news','1',$keyword['title'],$keyword['content']
                    ,SITE_URL . "/Uploads/" . $keyword['img'], $keyword['url']);
                ob_clean();
                exit($resultStr);
            }

        }

    }

    private function others(){
        $resultStr = '';
        switch($this->message['type']) {
            case "image"://图片消息
                $resultStr = '图片接口暂无法启用';
                break;
            case "voice"://语音消息
                $resultStr = '语音接口暂无法启用';
                break;
            case "video"://视频消息
                $resultStr = '视频消息接口暂无法启用';
                break;
            case "shortvideo"://小视频消息
                $resultStr = '小视频消息接口暂无法启用';
                break;
            case "location"://地理位置消息
                $resultStr = '地理位置消息接口暂无法启用';
                break;
            case "link"://链接消息
                $resultStr = '链接消息接口暂无法启用';
                break;
        }
        if($resultStr != '')
            $this->subscribe($resultStr);
    }


}