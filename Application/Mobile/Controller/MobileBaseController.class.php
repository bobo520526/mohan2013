<?php

namespace Mobile\Controller;
use Think\Controller;
use Home\Classlogic\Weixin_accountClasslogic;
use Home\Classlogic\RelationClasslogic;
class MobileBaseController extends Controller {
    public $user = array();
    public $account_id;
    public $wxuser = array();
    public $user_id = 0;
    public $session_id;
    public $weixin_config = array();
    public $handel_account;
    public $jump_url = '';

    /*
     * 初始化操作
     */
    public function _initialize() {
        $_SESSION['member_id'] = 2;
        $this->jump_url = SITE_URL . "/Mobile/".CONTROLLER_NAME . "/".ACTION_NAME."/account/".$_SESSION['account'].".html";
//         $this->session_id = session_id(); // 当前的 session_id
//         define('SESSION_ID',$this->session_id); //将当前的session_id保存为常量，供其它方法调用
//         // 判断当前用户是否手机                
//         if(isMobile())
//             cookie('is_mobile','1',3600); 
//         else 
//             cookie('is_mobile','0',3600);

//         $wid = $_GET['account'];
//         if($wid)
//             $wechat_config = M('wechat_config')->where(array('id'=>$wid))->find();
//         else
//             $wechat_config = M('wechat_config')->where(array('is_default'=>1))->find();
//         if(strstr($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') && !empty($wechat_config)){
//             $_SESSION['token'] = $wechat_config['token'];
//             $this->account_id = $wechat_config['id'];
//             $_SESSION['account'] = $this->account_id;
//             $this->handel_account = new Weixin_accountClasslogic($this->account_id);
//             $this->weixin_config = $wechat_config;
//             $fans = $this->base_get_openid();
//             if($fans)
//                 $this->deal_with_member($fans);
//             if($wechat_config && !$_SESSION['openid']){
//                 //去授权获取openid
//                 if(!$this->wxuser['subscribe']){
//                     //强制关注再操作
//                 }
//                 if(!$_SESSION['member_id']){
//                     $fans = $this->base_get_openid();
//                     if($fans)
//                         $this->deal_with_member($fans);
//                 }
//             }
//             // 微信Jssdk 操作类 用分享朋友圈 JS
// //            $signPackage = $this->handel_account->get_jssdk_sign_package();
// //            $this->assign('signPackage', $signPackage);
//         }
// //        echo "124";
// //        dd($_SESSION);
// //        if(!empty($_SESSION['openid'])){
// //            header("Location: " . SITE_URL . U('Mobile/Index/index',array('account'=>$_SESSION['account'])));
// //        }
//         $this->public_assign();
    }

    public function base_get_openid(){
        if($_SESSION['openid']){
            $account_member = M('member')->where(array('openid'=>$_SESSION['openid']))->find();
            if($account_member['token'] == $_SESSION['token']){
                return array('openid'=>$_SESSION['openid']);
            }else{
                unset($_SESSION['openid']);
                $this->get_openid();
            }
        }else{
            $this->get_openid();
        }
    }


    public function get_openid(){
        $handel_account = new Weixin_accountClasslogic($_SESSION['account']);
        $scope = 'snsapi_base';
        //通过code获得openid
        if (!isset($_GET['code'])){
            $baseUrl = SITE_URL . U('Mobile/MobileBase/get_openid',array('account'=>$_SESSION['account']));
            $handel_account->wechat_oauth_jump($baseUrl,$scope);
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $data = $handel_account->get_oauth_userInfo($code);
            if(!$data['errcode'] && $data['games'] === true){
                unset($data['games']);
                $wechat = M('wechat_config')->where(array('id'=>$_SESSION['account']))->find();
                $member_item = M('member')->where(array('token'=>$wechat['token'],'openid'=>$data['openid']))->find();
                if(!empty($member_item)){
                    $data['member_id'] = $member_item['member_id'];
                }else{
                    $fans['openid'] = $data['openid'];
                    $fans['token'] = $wechat['token'];
                    $fans['createtime'] = time();
                    $data['member_id'] = $fans['member_id'] = M('member')->add($fans);

                }
                $_SESSION['openid'] = $data['openid'];
                $_SESSION['member_id'] = $data['member_id'];
                $data['token'] = $_SESSION['token'];
                $this->wxuser = $data;
                $this->deal_with_member($data);
            }else{
                if($this->jump_url != '')
                    header("Location: " . $this->jump_url);
                else
                    header("Location: " . SITE_URL . U('Mobile/Index/index',array('account'=>$_SESSION['account'])));
            }
        }
    }

    public function deal_with_member($data){
        if(!$data['member_id'] && $data['openid']){
            $data = M('member')->where(array('openid'=>$_SESSION['openid']))->find();
        }
        $this->wxuser = $data;
        session('subscribe', $this->wxuser['subscribe']);// 当前这个用户是否关注了微信公众号
        session('member', $this->wxuser);// 当前这个用户授权基本信息
        session('member_id', $this->wxuser['member_id']);// 当前这个用户授权基本信息
        setcookie('member_id',$this->wxuser['member_id'],null,'/');
        setcookie('openid',$this->wxuser['openid'],null,'/');
        setcookie('uname',$this->wxuser['nickname'],null,'/');
        setcookie('nickname',$this->wxuser['nickname'],null,'/');
//        header("Location: " . SITE_URL . U('Mobile/Enduser/switching_equipment',array('account'=>$_SESSION['account_id'])));
    }

    public function return_wxuser(){
        return $this->wxuser;
    }


    /**
     * 保存公告变量到 smarty中 比如 导航 
     */   
    public function public_assign(){

        if(empty($_SESSION['openid'])) $this->get_openid();
    }      


}