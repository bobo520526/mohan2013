<?php
namespace Mobile\Controller;
use Think\Controller;
use Home\Classlogic\CommissionClasslogic;
use Mobile\Classlogic\CoinClasslogic;
class BasketballController extends MobileBaseController {
  /**
  * 篮球游戏运行
  **/
  public function index(){
    $class = new CoinClasslogic();
    $class :: get_distribution_config();
    $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
    $game_config = $class ::get_game_config();
    //时间
    $this->assign('time_config',empty($game_config)?30:$game_config['games_time']);
    //状态
    $this->assign("game_status",empty($game_config)?0:$game_config['game']['status']);
    $this->assign("msg",empty($game_config)?0:$game_config['game']['close_desc']);
    $this->display();
  }
  /**
  * 篮球操作
  *              参数 {     coin：总金币数量    }
  **/
  public function operate(){

    if(isset($_POST['coin']))//判断是否传入金币
      $coin=intval($_POST['coin']);
    else
      die(json_encode(array("status"=>-1)));


    //------------------------------------------------
    //查询用户金币余额
    $member_id=isset($_SESSION['member_id'])?intval($_SESSION['member_id']):1;
    $time=time();
    $app=M("wechat_config")->where("is_default=1")->find();
    if($ary=M("member")->where(array("member_id"=>$member_id))->find()){
        $ary['coin']=intval($ary['coin']);
        $hcoin=array(
          "member_id"=>$member_id,
          "appid"=>$app['appid'],
          "before_coin"=>$ary['coin'],
          "after_coin"=>$ary['coin']+$coin,
          "type"=>1,
          "desc"=>"用户投篮球获取金币".$coin."个",
          "createtime"=>$time
        );
        $ary['coin']=$ary['coin']+$coin;
        M("member_coin_detail")->add($hcoin);
        M("member")->save($ary);
          die(json_encode(array("status"=>1)));


    }
    else{//查询失败
        die(json_encode(array("status"=>-2)));
    }

  }
  /**
  * 获取用户金币 以及 扣除每局扣除金币
  **/
  public function getcoin(){
    $class = new CoinClasslogic();
    $class :: set_current_mid($_SESSION['member_id']);
    $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
    $class :: get_distribution_config();
    $coin=$class::get_deduction_coin_config();
    $member_id=isset($_SESSION['member_id'])?intval($_SESSION['member_id']):1;
    if($ary=M("member")->where(array("member_id"=>$member_id))->find()){
    $ary['coin']=intval($ary['coin']);
      echo  json_encode(array("status"=>$member_id,"price"=>$ary['coin'],"kprice"=>$coin));
      exit;
    }
    else{
      echo  json_encode(array("status"=>1,"price"=>0));
      exit;
    }

  }

  /**
   * 扣除玩家游戏
   */
  public function member_cut_coin(){
    $class = new CoinClasslogic();
    $class :: set_current_mid($_SESSION['member_id']);
    $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
    $class :: get_distribution_config();
    $before_coin=M("member")->where(array("member_id"=>$_SESSION['member_id']))->getField("coin");
    $res = $class :: calculation_coin_current_member();
    $after_coin=M("member")->where(array("member_id"=>$_SESSION['member_id']))->getField("coin");
    $appid=M("wechat_config")->where("is_default = 1")->getField("appid");
    $coin=$class::get_deduction_coin_config();
    $time=time();
    if($res){
      $member = M('member')->where(array('member_id'=>$_SESSION['member_id']))->find();
      session('member',$member);
      $ary=array(
        "member_id"=>$_SESSION['member_id'],
        "appid"=>$appid,
        "before_coin"=>$before_coin,
        "after_coin"=>$after_coin,
        "type"=>2,
        "desc"=>"用户开始投篮游戏扣除".$coin."个金币",
        "createtime"=>$time
      );
      M("member_coin_detail")->add($ary);
      die(json_encode(array("status"=>1,'coin'=>$member['coin'])));
    }
    else{
  die(json_encode(array("status"=>0,'msg'=>'数据操作异常！')));

  }
    //
  }

  /**
  *概率
  **/
  public function gl(){
    $class = new CoinClasslogic();
    $class :: get_distribution_config();
    $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
    $game_config = $class ::get_game_config();
    $game_gl=explode(",",$game_config['other_probability']);
    die(json_encode(array("status"=>1,'ary'=>$game_gl)));
  }
  /**
  *获取金币
  **/
  public function coin(){
    $class = new CoinClasslogic();
    $class :: get_distribution_config();
    $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
    $game_config = $class ::get_game_config();
    $game_coin=explode(",",$game_config['other_coins']);
    die(json_encode(array("status"=>1,'ary'=>$game_coin)));
  }


}
