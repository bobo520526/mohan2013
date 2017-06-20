<?php

namespace Mobile\Controller;

use Think\Controller;
use Home\Classlogic\CommissionClasslogic;
use Mobile\Classlogic\CoinClasslogic;

class CatchfishController extends MobileBaseController {

    /**
     * 捕鱼游戏运行
     * */
    public function index() {
        $class = new CoinClasslogic();
        $class :: get_distribution_config();
        $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
        $game_config = $class ::get_game_config();
        //时间
        $this->assign('time_config', empty($game_config) ? 30 : $game_config['games_time']);
        //用户金币
        $member_coin = M('member')->where(array('member_id' => $_SESSION['member_id']))->getField('coin');
        //    扣除金币
        $coin_config = $class :: get_deduction_coin_config();
        //游戏状态
        $this->assign("mem_coin", $member_coin);
        $this->assign("game_coin", $coin_config);
        $this->assign("game_status", empty($game_config) ? 0 : $game_config['game']['status']);
        $this->assign("msg", empty($game_config) ? 0 : $game_config['game']['close_desc']);
        $game_gl = explode(",", $game_config['other_probability']);
        $gl = array();
        foreach ($game_gl as $a) {
            $gl[] = explode("-", $a);
        }
        // var_dump($gl);die();
        // $gls = json_decode($gl);
        $this->assign("gl", $gl);

        $this->display();
    }

    /**
     * 捕鱼操作
     *              参数 {     coin：总金币数量    }
     * */
    public function operate() {
        if (isset($_POST['coin']))//判断是否传入金币
            $coin = intval($_POST['coin']);
        else
            die(json_encode(array("status" => -1)));


        //------------------------------------------------
        //查询用户金币余额
        $member_id = isset($_SESSION['member_id']) ? intval($_SESSION['member_id']) : 0;
        $time = time();
        $app = M("wechat_config")->where("is_default=1")->find();
        if ($ary = M("member")->where(array("member_id" => $member_id))->find()) {
            if (isset($_POST['type'])) {//实时修改金币
                $ary['coin'] = $coin;
                if (M("member")->save($ary)) {//修改成功
                    die(json_encode(array("status" => 1)));
                } else {
                    die(json_encode(array("status" => -1)));
                }
            }
            $ary['coin'] = intval($ary['coin']);
            $fish = array(
                "member_id" => $member_id,
                "coin" => $coin,
                "addtime" => $time
            );
            if ($id = M("catch_fish")->add($fish)) {
                $x = $coin - $ary['coin'];
                if ($x > 0) {//获得金币
                    $hcoin = array(
                        "member_id" => $member_id,
                        "appid" => $app['appid'],
                        "before_coin" => $ary['coin'],
                        "after_coin" => $coin,
                        "type" => 1,
                        "desc" => "用户玩捕鱼获取金币" . $x . "个",
                        "createtime" => $time
                    );
                    M("member_coin_detail")->add($hcoin);
                } else if ($x < 0) {//亏损金币
                    $x = -($x);
                    $hcoin = array(
                        "member_id" => $member_id,
                        "appid" => $app['appid'],
                        "before_coin" => $ary['coin'],
                        "after_coin" => $coin,
                        "type" => 2,
                        "desc" => "用户玩捕鱼亏损金币" . $x . "个 id=" . $id,
                        "createtime" => $time
                    );
                    M("member_coin_detail")->add($hcoin);
                }
                //成功返回1
                $ary['coin'] = $coin;
                M("member")->save($ary);
                $coin = M("member")->where(array("member_id" => $_SESSION['member_id']))->getField("coin");
                $class = new CoinClasslogic();
                $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
                $coin_config = $class :: get_deduction_coin_config();
                die(json_encode(array("status" => 1, "coin" => $coin, "game_coin" => $coin_config)));
            }
        } else {//查询失败
            die(json_encode(array("status" => -2)));
        }
    }

    /**
     * 获取用户金币
     * */
    public function getcoin() {
        $member_id = isset($_SESSION['member_id']) ? intval($_SESSION['member_id']) : 0;
        if ($member_coin = M('member')->where(array('member_id' => $member_id))->getField('coin')) {
            $member_coin = intval($member_coin);
            echo json_encode(array("status" => 1, "price" => $member_coin));
        } else {
            echo json_encode(array("status" => 1, "price" => 0));
        }
    }

    /**
     * 根据鱼ID获取金币
     * */
    public function getfishcoin() {
        /*
          $fid=isset($_POST['fid'])?intval($_POST['fid']):0;
          if($ary=M("fish")->where(array("fid"=>$fid))->find()){
          $ary['fishcoin']=intval($ary['fishcoin']);
          die(json_encode(array("fishcoin"=>$ary['fishcoin'])));
          }
          else{
          die(json_encode(array("fishcoin"=>0)));
          }
         */
        $fid = isset($_POST['fid']) ? intval($_POST['fid']) : 0;
        $class = new CoinClasslogic();
        $class :: get_distribution_config();
        $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
        $game_config = $class ::get_game_config();

        $game_coin = explode(",", $game_config['other_coins']);
        $coin = intval($game_coin[$fid]);
        die(json_encode(array("fishcoin" => $coin)));
    }

    /**
     * 扣除玩家游戏
     */
    public function member_cut_coin() {
        $class = new CoinClasslogic();
        $class :: set_current_mid($_SESSION['member_id']);
        $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
        $class :: get_distribution_config();
        $before_coin = M("member")->where(array("member_id" => $_SESSION['member_id']))->getField("coin");
        $res = $class :: calculation_coin_current_member();
        $after_coin = M("member")->where(array("member_id" => $_SESSION['member_id']))->getField("coin");
        $appid = M("wechat_config")->where("is_default = 1")->getField("appid");
        $coin = $class::get_deduction_coin_config();
        $time = time();
        if ($res) {
            $member = M('member')->where(array('member_id' => $_SESSION['member_id']))->find();
            session('member', $member);
            $ary = array(
                "member_id" => $_SESSION['member_id'],
                "appid" => $appid,
                "before_coin" => $before_coin,
                "after_coin" => $after_coin,
                "type" => 2,
                "desc" => "用户开始捕鱼游戏扣除" . $coin . "个金币",
                "createtime" => $time
            );
            M("member_coin_detail")->add($ary);
            die(json_encode(array("status" => 1, 'coin' => $member['coin'])));
        } else {
            die(json_encode(array("status" => 0, 'msg' => '数据操作异常！')));
        }
        //
    }

    /**
     * 捕鱼概率
     * */
    public function gl() {

        $index = isset($_POST['index']) ? intval($_POST['index']) : 0;
        if ($index < 0) {
            die(json_encode(array("status" => 0, 'msg' => '数据操作异常！')));
        }
        $class = new CoinClasslogic();
        $class :: get_distribution_config();
        $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
        $game_config = $class ::get_game_config();
        
        $game_gl = explode(",", $game_config['other_probability']);
        $ary = array();
        foreach ($game_gl as $a) {
            $ary[] = explode("-", $a);
        }
        
        die(json_encode(array("status" => 1, 'ary' => $ary)));
    }

}
