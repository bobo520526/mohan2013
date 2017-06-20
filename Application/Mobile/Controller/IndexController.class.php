<?php

namespace Mobile\Controller;

use Think\Controller;
use Home\Classlogic\Weixin_accountClasslogic;
use Mobile\Classlogic\ThirdpayClasslogic;
use Home\Classlogic\Wechat_payClasslogic;

class IndexController extends MobileBaseController {

    public $member_id = 0;
    public $openid = null;
    public $member = array();
    public $distribution_config = array();

    /*
     * 初始化操作
     */

    public function _initialize() {
        parent::_initialize();
        // 雷振兴添加开始
        $this->visit_count();
        // 雷振兴添加结束
        $member = $_SESSION['member'];
        $this->member = $member;

        $this->member_id = $member['member_id'];
        $this->openid = empty($member['openid']) ? '' : $member['openid'];
        if ($_SESSION['token'] != $member['token']) {
            parent::base_get_openid();
        }
        //重新赋值
        if (!empty($_SESSION['openid']) || !empty($_SESSION['member_id'])) {
            $member = M('member')->where(array('token' => $_SESSION['token'], 'openid' => $_SESSION['openid']))->find();
            $this->member = $member;
            $this->member_id = $member['member_id'];
            $this->openid = empty($member['openid']) ? '' : $member['openid'];
        }
        $this->assign('member', $member); //存储用户信息
        if (!$this->member_id || !$this->openid) {
            //重新获取微信openid 进行操作
        }
        $this->distribution_config = M('distribution')->order('id desc')->limit(1)->find();
        $this->assign('distribution_config', $this->distribution_config);
    }

    public function test() {
        var_dump(THINK_PATH);
    }

    public function index() {
        vendor("phpemoji.emoji");
        $this->assign('member', $this->member);
        if (empty($this->member['nickname']))
            $nickname = "获取昵称失败";
        else
            $nickname = $this->member['nickname'];
        $this->assign('nickname', emoji_unified_to_html($nickname));
        $this->assign('member_id', $this->member['member_id'] + 101011);

        //累计提现金额
        $my_withdrawals = M('withdrawal')->where(array('member_id' => $this->member['member_id'], 'apply_status' => 1))->sum('apply_cash');
        $this->assign('my_withdrawals', empty($my_withdrawals) ? 0 : $my_withdrawals);
        //累计佣金
        $my_commission = M('commission_log')->where(array('parent_member_id' => $this->member['member_id']))->sum('commission');
        $this->assign('my_commission', empty($my_commission) ? 0 : $my_commission);
        //累计金币
        $my_price = M('member')->where(array('member_id' => $this->member['member_id']))->select();
        $this->assign('redcount', empty($my_price[0]['coin']) ? 0 : $my_price[0]['coin']);
        //累计未开红包
        $my_red = M('red_count')->where(array('member_id' => $this->member['member_id']))->select();
        $this->assign('redc', empty($my_red) ? 0 : $my_red[0]['redcount']);
        $_SESSION['red_id'] = $my_red[0]['id'];
        $this->display("personal");
    }

    public function gold() {
        $this->display("goldrec");
    }

    public function extension() {

        // session('member_id',1);
        $member = M('member')->where(array('member_id' => session('member_id')))->find();
        if (empty($member['qrcode'])) {
            vendor("phpqrcode.phpqrcode");
            $qrcode = new \QRcode();
            //分开目录存储二维码图
            if (!is_dir('./Public/phpqrcode/')) {
                mkdir('./Public/phpqrcode/');
            }
            $wechat = M('wechat_config')->where(array('is_default' => 1))->find();
            $str = get_rand_str(8, 0, 1);
            $qrcode_link = './Public/phpqrcode/' . $str . '_' . session('member_id') . '.png';
            $url = SITE_URL . '/index.php/Mobile/Public/auth/openid/' . $member['openid'] . "/parent_id/" . $member['member_id'] . "/account/" . $wechat['id'];
            ob_clean();
            $qrcode::png($url, $qrcode_link, 'L', 5, 4, false);
            $qrcode_link = ltrim($qrcode_link, '.');
            M('member')->where(array('member_id' => session('member_id')))->save(array('qrcode' => $qrcode_link));
        } else {
            $qrcode_link = $member['qrcode'];
        }
        $this->assign('qrcode_link', SITE_URL . $qrcode_link);


        $this->display("qrcode");
    }

    public function withdrawals() {

        $list = M('withdrawal')->where(array('member_id' => $this->member['member_id']))->select();
        $this->assign('list', $list);
        $this->assign('status', array('审核中', '已通过', '已拒绝'));
        $this->assign('status_css', array('yellow', 'blur', 'red'));

        $this->display();
    }

    public function apply_withdrawals() {

        //累计提现金额
        $my_withdrawals = M('withdrawal')->where(array('member_id' => $this->member['member_id'], 'apply_status' => 1))->sum('apply_cash');
        $this->assign('my_withdrawals', empty($my_withdrawals) ? 0 : $my_withdrawals);
        //累计佣金
        $my_commission = M('commission_log')->where(array('parent_member_id' => $this->member['member_id']))->sum('commission');
        $this->assign('my_commission', empty($my_commission) ? 0 : $my_commission);

        $this->assign('member', $this->member);
        if (IS_POST) {
            $apply_cash = sprintf("%.2f", $_POST['apply_cash']);
            if ($apply_cash <= 0) {
                die(json_encode(array('state' => 0, 'msg' => '提现金额错误！')));
            }
            if ($apply_cash > $this->member['wallet']) {
                die(json_encode(array('state' => 0, 'msg' => '提现金额已超账户余额！')));
            }
            //先减去钱包余额操作
            $after_wallet = $this->member['wallet'] - $apply_cash;
            $res = M('member')->where(array('member_id' => $this->member['member_id']))->save(array('wallet' => $after_wallet));
            if ($res) {
                $wechat_config = M('wechat_config')->where(array('is_default' => 1))->find();
                $this->member['wallet'] = $after_wallet;
                //增加记录明细
                $wallet_detail = array(
                    'member_id' => $this->member['member_id'],
                    'before_wallet' => $this->member['wallet'],
                    'after_wallet' => $after_wallet,
                    'appid' => $wechat_config['appid'],
                    'type' => 2,
                    'desc' => '用户提现操作',
                    'createtime' => time(),
                );
                M('member_wallet_detail')->add($wallet_detail);
                //增加提现管理
                $withdrawals_data = array(
                    'member_id' => $this->member['member_id'],
                    'apply_cash' => $apply_cash,
                    'apply_status' => 0,
                    'appid' => $wechat_config['appid'],
                    'after_apply_cash' => $after_wallet,
                    'createtime' => time()
                );
                M('withdrawal')->add($withdrawals_data);
                die(json_encode(array('state' => 1, 'msg' => '申请提现成功，请等待审核')));
            }
            die(json_encode(array('state' => 0, 'msg' => '申请提现失败')));
        }
        $this->display();
    }

    public function add_withdrawal_coin() {
        if ($this->distribution_config['coin_off'] != 1) {
            html_tips("金币提现功能暂未开启");
        }
        $this->assign('member', $this->member);
        $this->assign('distribution_config', $this->distribution_config);
        if (IS_POST) {
            $apply_cash = sprintf("%.2f", $_POST['apply_cash']);
            if ($apply_cash <= 0) {
                die(json_encode(array('state' => 0, 'msg' => '提现金币个数错误！')));
            }
            if ($apply_cash > $this->member['coin']) {
                die(json_encode(array('state' => 0, 'msg' => '提现金币个数已超账户总个数！')));
            }
            if ($apply_cash < $this->distribution_config['min_coin']) {
                die(json_encode(array('state' => 0, 'msg' => '少于系统最小提现个数！')));
            }
            //先减去钱包余额操作
            $after_coin = $this->member['coin'] - $apply_cash;
            $res = M('member')->where(array('member_id' => $this->member['member_id']))->save(array('coin' => $after_coin));
            if ($res) {
                $wechat_config = M('wechat_config')->where(array('is_default' => 1))->find();
                $this->member['coin'] = $after_coin;
                //增加记录明细
                $wallet_detail = array(
                    'member_id' => $this->member['member_id'],
                    'before_coin' => $this->member['coin'],
                    'appid' => $wechat_config['appid'],
                    'type' => 2,
                    'after_coin' => $after_coin,
                    'desc' => '用户提现金币操作',
                    'createtime' => time(),
                );
                M('member_coin_detail')->add($wallet_detail);
                //增加提现管理

                $withdrawals_data = array(
                    'member_id' => $this->member['member_id'],
                    'apply_coin' => $apply_cash,
                    'appid' => $wechat_config['appid'],
                    'apply_status' => 0,
                    'check_type' => $this->distribution_config['coin_check'],
                    'after_apply_cash' => $after_coin,
                    'createtime' => time()
                );
                $msg = '申请提现成功，请等待审核';
                $url = '';
                $wid = M('withdrawal_coin')->add($withdrawals_data);
                /**
                 * type 提现类型 1 表示金币提现 2表示账户余额提现
                 * check_type 是否需要审核 1 表示不需要 2表示需要审核
                 */
                $param = array(
                    'wid' => $wid,
                    'type' => 1,
                    'check_type' => 1,
                    'returnUrl' => $pay_param_str = base64_encode(json_encode(SITE_URL . U('Mobile/Index/index')))
                );
                //金币提现是否需要审核
                if ($this->distribution_config['coin_check'] == 2) {
                    //不需要审核 直接调取接口操作
//                    $class = new Weixin_accountClasslogic($wechat_config['id']);
//                    $class->set_openid($this->member['openid']);
//                    $class->set_amount($apply_cash);
//                    $res = $class->get_pay_withdrawal();
//                    if($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS'){
//                        $withdrawals_data['partner_trade_no'] = $res['partner_trade_no'];
//                        $withdrawals_data['payment_no'] = $res['payment_no'];
//                        $withdrawals_data['payment_time'] = strtotime($res['payment_time']);
//                        $withdrawals_data['apply_status'] = 1;
//                        $msg = '申请提现已到账，请查收';
//                    }

                    if ($apply_cash >= 100 && $this->distribution_config['coin_num_check'] == 1) {
                        $param['check_type'] = 2;
                        $url = SITE_URL . U('Mobile/DistributionWechat/auth', $param);
                    } else {
                        $msg = '提现正在执行，请勿操作！';
                        $url = SITE_URL . U('Mobile/DistributionWechat/auth', $param);
                    }
                    //$class = new Wechat_payClasslogic();
                } else {
                    //需要审核操作
                    $param['check_type'] = 2;
                    $url = SITE_URL . U('Mobile/DistributionWechat/auth', $param);
                }
                die(json_encode(array('state' => 1, 'msg' => $msg, 'url' => $url)));
            }
            die(json_encode(array('state' => 0, 'msg' => '申请提现失败')));
        }
        $this->display();
    }

    public function pay() {
        $wechat = M('wechat_config')->where(array('is_default' => 1))->find();

        $order = array(
            'charge_money' => 0.01,
            'member_id' => $_SESSION['member_id'],
            'openid' => $_SESSION['openid'],
            'order_sn' => getOrderSn(),
            'coin' => 1,
            'appid' => $wechat['appid'],
            'charge_proportion' => 1,
            'status' => 0,
            'pay_code' => 'weixin',
            'createtime' => time(),
        );
        $order['order_id'] = M('order')->add($order);
//    $order = M('order')->where(array('order_id'=>113))->find();
        /**
         * 跳转支付
         */
        $ps = array(
            'appid' => $wechat['appid'],
            'order_id' => $order['order_id'],
            'openid' => $_SESSION['openid'],
            'member_id' => $order['member_id'],
        );
        $sl = base64_encode(json_encode($ps));
        if ($wechat['pay_type'] == 1)
            $class_pay = new Weixin_accountClasslogic($wechat['id']);
        else {
            $class_pay = new ThirdpayClasslogic();
            $class_pay->set_callback_url(SITE_URL . "/index.php?m=Mobile&c=Redpackets&a=recharge");
            $class_pay->set_notify_url(SITE_URL . '/payment/wxpay/third_notify.php');
            $order['attach'] = $wechat['id'] . "_" . $order['order_id'];
        }
        $class_pay->set_order_data($order);
        $pay_param = $class_pay->wechat_pay_build();
        if ($pay_param['status'] == 500) {
            html_tips($pay_param['msg']);
            exit();
        }
//        dd($pay_param);
//        $other_pay_info = json_decode($pay_param['pay_info'],true);
//        $pay_info = array(
//            'appId'=>$other_pay_info['appId'],
//            'timeStamp'=>$other_pay_info['timeStamp'],
//            'nonceStr'=>$other_pay_info['nonceStr'],
//            'package'=>$other_pay_info['package'],
//            'signType'=>$other_pay_info['signType'],
//            'paySign'=>$other_pay_info['paySign'],
//        );
//        if(is_error($pay_param)){
//            die(json_encode(array('state'=>0,'msg'=>$pay_param['message'])));
////            html_tips($pay_param['message']);
////            exit();
//        }
        $pay_param_str = base64_encode(json_encode($pay_param));
        $url = SITE_URL . "/payment/wxpay/pay.php?auth=" . $pay_param_str . "&ps=" . $sl;
        header("location: $url");
        die(json_encode(array('state' => 1, 'url' => $url)));
    }

    public function pay_return() {
        
    }

    public function pay_cancel() {
        
    }

    public function gamelist() {
        // 雷振兴添加开始
        $Weixin_accountClasslogic = new Weixin_accountClasslogic(4);
        $getSignPackage = $Weixin_accountClasslogic->getSignPackage();
        // var_dump($getSignPackage);die();
        // 雷振兴添加结束
        $ary = M("games")->where(array("status" => 1))->select();
        $this->assign("ary", $ary);
        // var_dump($ary);die();
        $this->assign("getSignPackage", $getSignPackage);
        $this->display("GameCentre");
    }

    //用户访问量
    public function visit_count() {
        $res = M('visit_num')->order('visit_time desc')->find();
        $data = array();
        $data['visit_count'] = 0;
        $data['visit_time'] = time();
        if (empty($res)) {
            M('visit_num')->add($data);
        } else {
            $arr = array();
            $arr['visit_count'] = $res['visit_count'] + 1;
            $now_time = date('Y-m-d', time());
            $visit_id = $res['visit_id'];
            if ($now_time == date('Y-m-d', $res['visit_time'])) {
                M('visit_num')->where("visit_id =$visit_id")->save($arr);
            } else {
                var_dump(2);
                M('visit_num')->add($data);
            }
        }
    }

}
