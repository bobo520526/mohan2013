<?php
namespace Mobile\Controller;
use Think\Controller;
use Home\Classlogic\Weixin_accountClasslogic;
class ActivityController extends MobileBaseController {

    public $member_id = 0;
    public $openid = null;
    public $member = array();
    public $distribution_config = array();

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
        $this->distribution_config = M('distribution')->order('id desc')->limit(1)->find();
        $this->assign('distribution_config',$this->distribution_config);
    }

    public function index(){
        $activity = M('activity')->where(array('status'=>1))->order('aid desc')->limit(1)->find();
        if(empty($activity) || $activity['status'] != 1){
            html_tips("活动未开始");
        }
        $images = explode(",",$activity['images']);
        $this->assign('images',$images);
        $wechat_config = M('wechat_config')->where(array('is_default'=>1))->find();
        $handler = new Weixin_accountClasslogic($wechat_config['id']);

        $signPackage = $handler->get_jssdk_sign_package();
        $this->assign('signPackage', $signPackage);
        $this->assign('activity', $activity);

        $this->assign('default_url',SITE_URL.U('Mobile/Activity/index'));

        $this->display();
    }

    public function detail(){
        $this->display();
    }
    public function confirm(){
        $this->display();
    }

    public function reconfirm(){
        $count = I('count',0);
        if($count < 1 || $count > 5){
            html_tips("购买数量不正确");
        }
    }

}
