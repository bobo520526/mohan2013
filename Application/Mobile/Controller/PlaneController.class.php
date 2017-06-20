<?php

namespace Mobile\Controller;

use Think\Controller;
use Home\Classlogic\CommissionClasslogic;
use Mobile\Classlogic\CoinClasslogic;

class PlaneController extends MobileBaseController {

    /**
     * 飞机大战
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
    
    public function operate(){
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
                "desc" => "用户捡到宝箱获取金币" . $coin . "个",
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
     * 获取用户信息
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
                    $getdata = array(
                        "status" => 1, 
                        'scoreNum' => $last_coin, 
                        'system_cut_coin' => $system_cut_coin, 
                        'games_time' => $games_time, 
                        'other_coins' => $other_coins, 
                        'user_photo' => $user_photo,
                        'nickname'   => $ary['nickname']
                    );
                    echo json_encode($getdata);
                    exit;
                } else {
                    $getdata = array(
                        "status" => 0,
                        'scoreNum' => 0,
                        'system_cut_coin' => 0, 
                        'games_time' => $games_time,
                        'other_coins' => 0,
                        'user_photo' => $user_photo,
                        'nickname'   => $ary['nickname']
                    );
                    echo json_encode($getdata);
                    exit;
                }
            } else {
                $getdata = array(
                    "status" => 3, 
                    'scoreNum' => 0, 
                    'system_cut_coin' => 0, 
                    'games_time' => $games_time, 
                    'other_coins' => 0, 
                    'user_photo' => $user_photo,
                    'nickname'   => $ary['nickname']
                );
                echo json_encode($getdata);
                exit;
            }
        }
    }

   
}
