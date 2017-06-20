<?php
namespace Mobile\Controller;
use Think\Controller;
use Home\Classlogic\Weixin_accountClasslogic;
use Home\Classlogic\RelationClasslogic;
class PublicController extends Controller {
    public $user = array();
    public $account_id;
    public $wxuser = array();
    public $session_id;
    public $weixin_config = array();
    public $handel_account;


    public function __construct(){
        parent::__construct();
        $_SESSION['account'] = $_GET['account'];
        $_SESSION['openid'] = $_GET['openid'];
        $_SESSION['parent_id'] = $_GET['parent_id'];
    }

    public function auth(){
        $handel_account = new Weixin_accountClasslogic($_SESSION['account']);
        $scope = 'snsapi_base';
        //通过code获得openid
        if (!isset($_GET['code'])){

            $baseUrl = SITE_URL . U('Mobile/Public/auth',array('account'=>$_SESSION['account'],'openid'=>$_SESSION['openid'],'parent_id'=>$_SESSION['parent_id']));
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
                    $member_parent = M('member')->where(array('parent_id'=>$_REQUEST['parent_id'],'token'=>$wechat['token']))->find();

                    $fans['member_parent_id'] = $member_parent['member_id'];
                    $fans['openid'] = $data['openid'];
                    $fans['token'] = $wechat['token'];
                    $fans['createtime'] = time();
                    $data['member_id'] = $fans['member_id'] = M('member')->add($fans);
                    //建立关系
                    $relation_class = new RelationClasslogic();
                    $return = $relation_class :: generate($fans);
                }
                $_SESSION['openid'] = $data['openid'];
                $_SESSION['member_id'] = $data['member_id'];
                $data['token'] = $wechat['token'];
                $this->wxuser = $data;
                $this->deal_with_member($data);
            }else{
//                if(!empty($this->checkout))
//                    header("Location: " . SITE_URL . U('Mobile/Enduser/switching_equipment',array('account'=>$_SESSION['account_id'])));
//                else
                header("Location: " . SITE_URL . U('Mobile/Public/auth',array('account'=>$_SESSION['account'],'openid'=>$_SESSION['openid'],'parent_id'=>$_SESSION['parent_id'])));
            }
        }
    }


    public function deal_with_member($data){

        $this->wxuser = $data;
        session('subscribe', $this->wxuser['subscribe']);// 当前这个用户是否关注了微信公众号
        session('member', $this->wxuser);// 当前这个用户授权基本信息
        session('member_id', $this->wxuser['member_id']);
        setcookie('member_id',$this->wxuser['member_id'],null,'/');
        setcookie('openid',$this->wxuser['openid'],null,'/');
        header("Location: " . SITE_URL . U('Mobile/Index/index',array('account'=>$_SESSION['account'])));
    }


}