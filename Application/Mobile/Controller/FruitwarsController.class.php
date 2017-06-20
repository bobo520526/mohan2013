<?php

namespace Mobile\Controller;

use Think\Controller;
use Home\Classlogic\CommissionClasslogic;
use Mobile\Classlogic\CoinClasslogic;

class FruitwarsController extends MobileBaseController {

    /**
     * 进击的水果运行
     * */
    public function index() {
        // var_dump($this->jump_url);die();
        $class = new CoinClasslogic();
        $class :: get_distribution_config();
        $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
        $game_config = $class ::get_game_config();
        // 雷振兴   查询用户金币
        $number = SESSION('member_id');
        $coin = M('member')->WHERE("member_id=$number")->find();
        if (empty($coin)) {
            die('用户不存在');
        }
        $this->assign('coin', $coin['coin']);
        // 雷振兴结束
        // 游戏分秒
        //状态
        $this->assign("game_status", empty($game_config) ? 0 : $game_config['game']['status']);
        $this->assign("msg", empty($game_config) ? 0 : $game_config['game']['close_desc']);
        $this->display();
    }

    /**
     * 篮球操作
     *              参数 {     coin：总金币数量    }
     * */
    public function operate() {

        if (isset($_POST['coin'])) { //判断是否传入金币
            $coin = intval($_POST['coin']);
        } else {
            die(json_encode(array("status" => -1)));
        }

        //------------------------------------------------
        //查询用户金币余额
        $member_id = isset($_SESSION['member_id']) ? intval($_SESSION['member_id']) : 0;
        $time = time();
        $app = M("wechat_config")->where("is_default=1")->find();
        if ($ary = M("member")->where(array("member_id" => $member_id))->find()) {
            $ary['coin'] = intval($coin);
            $hcoin = array(
                "member_id" => $member_id,
                "appid" => $app['appid'],
                "before_coin" => $ary['coin'],
                "after_coin" => $coin,
                "type" => 1,
                "desc" => "用户进击水果获取金币" . intval($coin - $ary['coin']) . "个",
                "createtime" => $time
            );
            $ary['coin'] = $coin;  //最终插入金币数量
            M("member_coin_detail")->add($hcoin);
            M("member")->save($ary);
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
        $cut_score = explode(',', $game_config['other_coins']); //游戏中子弹扣除金币
        $get_score = explode(',', $game_config['other_probability']); //游戏中打中获得金币
        $system_cut_coin = $game_config['min_coin']; //系统配置开局消耗金币
        $arr = changeTimeType($game_config['games_time'], ',');
        $member_id = isset($_SESSION['member_id']) ? intval($_SESSION['member_id']) : 0;
        $ary = M("member")->where("member_id=$member_id")->find();
        // $bullet_cut_coin_index=array_search(max(explode(',',$game_config['other_coins'])),explode(',',$game_config['other_coins'])); //发射子弹消耗最大金币
        if (!empty($ary)) {
            $ary['coin'] = intval($ary['coin']);
            $last_coin = $ary['coin'] - $system_cut_coin;
            if ($last_coin > 0) {
                $data = array();
                $data['coin'] = $last_coin;
                $res = M('member')->where("member_id=$member_id")->save($data);
                if ($res !== false) {
                    echo json_encode(array("status" => 1, 'scoreNum' => $last_coin, 'hour' => $arr[0], 'min' => $arr[1], 'secend' => $arr[2], 'cut_score_one' => $cut_score[0], 'cut_score_two' => $cut_score[1], 'cut_score_three' => $cut_score[2], 'get_score_one' => $get_score[0], 'get_score_two' => $get_score[1], 'get_score_three' => $get_score[2]));
                    exit;
                } else {
                    echo json_encode(array("status" => 0, 'scoreNum' => 0, 'hour' => $arr[0], 'min' => $arr[1], 'secend' => $arr[2], 'cut_score_one' => $cut_score[0], 'cut_score_two' => $cut_score[1], 'cut_score_three' => $cut_score[2], 'cut_score_one' => $cut_score[0], 'cut_score_two' => $cut_score[1], 'cut_score_three' => $cut_score[2], 'get_score_one' => $get_score[0], 'get_score_two' => $get_score[1], 'get_score_three' => $get_score[2]));
                    exit;
                }
            } else {
                echo json_encode(array("status" => 3, 'scoreNum' => 0, 'hour' => $arr[0], 'min' => $arr[1], 'secend' => $arr[2]));
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
