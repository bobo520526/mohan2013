<?php

namespace Mobile\Controller;

use Think\Controller;
use Home\Classlogic\CommissionClasslogic;
use Mobile\Classlogic\CoinClasslogic;

class PumpkinController extends MobileBaseController {

    /**
     * 进击的水果运行
     * */
    public function index() {
        // $class = new CoinClasslogic();
        // $class :: get_distribution_config();
        // $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
        // $game_config = $class ::get_game_config();
        // // 雷振兴   查询用户金币
        // $number = SESSION('member_id');
        // $coin = M('member')->WHERE("member_id=$number")->find();
        // if (empty($coin)) {
        //     die('用户不存在');
        // }
        $this->display();
    }

    /**
     * 篮球操作
     *              参数 {     coin：总金币数量    }
     * */
    public function operate() { //游戏结束后操作
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
            $hcoin = array(
                "member_id" => $member_id,
                "appid" => $app['appid'],
                "before_coin" => $ary['coin'],
                "after_coin" => $coin,
                "type" => 1,
                "desc" => "用户爆走的南瓜获取金币" . $coin . "个",
                "createtime" => $time
            );
            $total_coin = $ary['coin'] + $coin;
            $ary['coin'] = $total_coin;  //获得总共金币
            M("member_coin_detail")->add($hcoin);
            M("member")->where('member_id=' . $member_id)->save($ary);
            die(json_encode(array("status" => 1)));
        } else {//查询失败
            die(json_encode(array("status" => -2)));
        }
    }

    /**
     * 获取用户金币 以及 扣除每局扣除金币
     * */
    public function getcoin() {
        $class = new CoinClasslogic();
        $class :: get_distribution_config();
        $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
        $game_config = $class ::get_game_config();
        $system_cut_coin = $game_config['min_coin']; //系统配置开局消耗金币
        $games_time = $game_config['games_time'] * 1000;
        $str = explode(',', $game_config['other_coins']); //游戏其他配置其他
        $other_coins = $str[0];
        $member_id = isset($_SESSION['member_id']) ? intval($_SESSION['member_id']) : 0;
        $ary = M("member")->where("member_id=$member_id")->find();
        if (!empty($ary)) {
            $user_photo = $ary['head_pic'];
            if (empty($user_photo)) {
                $user_photo = -1;
            }
            $ary['coin'] = intval($ary['coin']);
            $last_coin = $ary['coin'] - $system_cut_coin;
            if ($last_coin >= 0) {
                $data = array();
                $data['coin'] = $last_coin;
                $res = M('member')->where("member_id=$member_id")->save($data);
                if ($res !== false) {
                    echo json_encode(array("status" => 1, 'scoreNum' => $last_coin, 'system_cut_coin' => $system_cut_coin, 'games_time' => $games_time, 'other_coins' => $other_coins, 'user_photo' => $user_photo));
                    exit;
                } else {
                    echo json_encode(array("status" => 0, 'scoreNum' => 0, 'system_cut_coin' => 0, 'games_time' => $games_time, 'other_coins' => 0, 'user_photo' => $user_photo));
                    exit;
                }
            } else {
                echo json_encode(array("status" => 3, 'scoreNum' => 0, 'system_cut_coin' => 0, 'games_time' => $games_time, 'other_coins' => 0, 'user_photo' => $user_photo));
                exit;
            }
        }
    }

    // /**
    //  * 扣除玩家游戏
    //  */
    // public function member_cut_coin(){
    //   $class = new CoinClasslogic();
    //   $class :: set_current_mid($_SESSION['member_id']);
    //   $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
    //   $class :: get_distribution_config();
    //   $before_coin=M("member")->where(array("member_id"=>$_SESSION['member_id']))->getField("coin");
    //   $res = $class :: calculation_coin_current_member();
    //   $after_coin=M("member")->where(array("member_id"=>$_SESSION['member_id']))->getField("coin");
    //   $appid=M("wechat_config")->where("is_default = 1")->getField("appid");
    //   $coin=$class::get_deduction_coin_config();
    //   $time=time();
    //   if($res){
    //     $member = M('member')->where(array('member_id'=>$_SESSION['member_id']))->find();
    //     session('member',$member);
    //     $ary=array(
    //       "member_id"=>$_SESSION['member_id'],
    //       "appid"=>$appid,
    //       "before_coin"=>$before_coin,
    //       "after_coin"=>$after_coin,
    //       "type"=>2,
    //       "desc"=>"用户开始投篮游戏扣除".$coin."个金币",
    //       "createtime"=>$time
    //     );
    //     M("member_coin_detail")->add($ary);
    //     die(json_encode(array("status"=>1,'coin'=>$member['coin'])));
    //   }
    //   else{
    // die(json_encode(array("status"=>0,'msg'=>'数据操作异常！')));
    // }
    //   //
    // }
    // /**
    // *概率
    // **/
    // public function gl(){
    //   $class = new CoinClasslogic();
    //   $class :: get_distribution_config();
    //   $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
    //   $game_config = $class ::get_game_config();
    //   $game_gl=explode(",",$game_config['other_probability']);
    //   die(json_encode(array("status"=>1,'ary'=>$game_gl)));
    // }
    // /**
    // *获取金币
    // **/
    // public function coin(){
    //   $class = new CoinClasslogic();
    //   $class :: get_distribution_config();
    //   $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
    //   $game_config = $class ::get_game_config();
    //   $game_coin=explode(",",$game_config['other_coins']);
    //   die(json_encode(array("status"=>1,'ary'=>$game_coin)));
    // }
}
