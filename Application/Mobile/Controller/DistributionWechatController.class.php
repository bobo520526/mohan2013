<?php
namespace Mobile\Controller;
use Think\Controller;
use Home\Classlogic\Wechat_payClasslogic;
class DistributionWechatController extends Controller {
    public $user = array();
    public $account_id;
    public $wxuser = array();
    public $session_id;
    public $weixin_config = array();
    public $handel_account;


    public function __construct(){
        parent::__construct();
        $_SESSION['wid'] = $_GET['wid'];
        $_SESSION['returnUrl'] = $_GET['returnUrl'];
        /**
         * type 提现类型 1 表示金币提现 2表示账户余额提现
         * check_type 是否需要审核 1 表示不需要 2表示需要审核
         */
        $_SESSION['type'] = $_GET['type'];
        $_SESSION['check_type'] = $_GET['check_type'];
    }

    public function auth(){
        $handel_account = new Wechat_payClasslogic();
        $scope = 'snsapi_base';
        $param = array(
            'wid'=>$_SESSION['wid'],
            'type'=>$_SESSION['type'],
            'check_type'=>$_SESSION['check_type'],
            'returnUrl'=>$_SESSION['returnUrl']
        );
        //通过code获得openid
        if (!isset($_GET['code'])){
            $baseUrl = SITE_URL . U('Mobile/DistributionWechat/auth',$param);
            $handel_account->wechat_oauth_jump($baseUrl,$scope);
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $data = $handel_account->get_oauth_userInfo($code);

            if(!$data['errcode'] && $data['games'] === true){
                unset($data['games']);
                if(empty($_SESSION['returnUrl']))
                    $succUrl = SITE_URL . U('Mobile/Index/index');
                else
                    $succUrl = $result_data = @json_decode(base64_decode($_SESSION['returnUrl']), true);
                //$data['openid']
//                $wechat = M('distribution_wechat')->order('id desc')->limit(1)->find();

                $withdrawal_coin = M('withdrawal_coin')->where(array('id'=>$_SESSION['wid']))->find();
                $insert = array(
                    'withdrawal_coin_id'=>$_SESSION['wid'],
                    'type'=>1,
                    'openid'=>$data['openid'],
                    'member_id'=>$withdrawal_coin['member_id'],
                    'createtime'=>time(),
                );
                if($_SESSION['check_type'] == 1){
                    $class = new Wechat_payClasslogic();
                    $class->set_openid($data['openid']);
                    $class->set_amount($withdrawal_coin['apply_coin']);
                    $res = $class->get_pay_withdrawal();
                    if($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS'){
                        $insert['status'] = 1;
                        $insert['updatetime'] = time();
                        M('other_pay')->add($insert);
                        $withdrawals_data['partner_trade_no'] = $res['partner_trade_no'];
                        $withdrawals_data['payment_no'] = $res['payment_no'];
                        $withdrawals_data['payment_time'] = strtotime($res['payment_time']);
                        $withdrawals_data['apply_status'] = 1;
                        M('withdrawal_coin')->where(array('id'=>$withdrawal_coin['id']))->save($withdrawals_data);
                        html_tips("申请提现已到账，请查收",true,$succUrl,"成功提示");
                        exit();
                    }
                    M('other_pay')->add($insert);
                    html_tips("申请提现失败",true,$succUrl);
                    exit();
                }else{
                    M('other_pay')->add($insert);
                    html_tips("申请提现成功，请等待审核",true,$succUrl,"成功提示");
                    exit();
                }
            }else{
                header("Location: " . SITE_URL . U('Mobile/DistributionWechat/auth',$param));
            }
        }
    }



}