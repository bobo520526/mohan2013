<?php
namespace Mobile\Controller;
use Think\Controller;
use Home\Classlogic\CommissionClasslogic;
use Mobile\Classlogic\CoinClasslogic;
class RedpacketsController extends MobileBaseController {
  /**
  *  抢红包运行。。。。
  **/
  public function index(){
    $member_coin = M('member')->where(array('member_id'=>$_SESSION['member_id']))->getField('coin');

    $class = new CoinClasslogic();
    $class :: get_distribution_config();
    $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
    $coin_config = $class :: get_deduction_coin_config();
    $game_config = $class ::get_game_config();
    if($coin_config === null) $coin_config = 0;
    $this->assign('coin_config',$coin_config);
    $this->assign('member_coin',$member_coin);
    $this->assign('games_time',$game_config['games_time']*1000);
    //页面时间显示
    //十位
    $decade = substr($game_config['games_time'],0,1);
    //个位
    $unit = substr($game_config['games_time'],1,1);
    //页面时间显示结束
    $this->assign('decade',$decade);
    $this->assign('unit',$unit);
    $this->assign('games_state',$game_config['game']['status']);
    $this->display();
  }

  /**
   * 扣除玩家游戏
   */
  public function member_cut_coin(){
    $class = new CoinClasslogic();
    $class :: set_current_mid($_SESSION['member_id']);
    $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
    $class :: get_distribution_config();
    $res = $class :: calculation_coin_current_member();
    if($res){
      $member = M('member')->where(array('member_id'=>$_SESSION['member_id']))->find();
      session('member',$member);
      die(json_encode(array("state"=>1,'coin'=>$member['coin'])));
    }else{
      die(json_encode(array("state"=>0,'msg'=>'数据操作异常12！')));
    }
  }

  /**
  *红包操作 返回 1.开启金币数量 2.是否开启成功  1 成功 2 失败
  **/

  public function operate(){
    if(I('s') == 1 && I('open_red_pack')){
        $member_id=isset($_SESSION['member_id'])?$_SESSION['member_id']:0;
        $openid=isset($_SESSION['openid'])?$_SESSION['openid']:" ";
        $red_id=isset($_SESSION['red_id'])?$_SESSION['red_id']:0;

        $wechat_conif=M("wechat_config")->where("is_default = 1")->find();

        $class = new CoinClasslogic();
        $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
        $open_red_pack = I('open_red_pack');
        $price_show = array();
        for ($i=0; $i < $open_red_pack; $i++) { 
          $red_count = M('red_count')->where(array("id"=>$red_id))->getField('redcount');
          $price =abs(sprintf("%.2f", $class :: get_coin_generate_rule() * $class :: get_deduction_coin_config()/$red_count));
          $time=time();
          $ary=array(
            "member_id"=>$member_id,
            "red_id"=>$red_id,
            "addtime"=>$time,
            "redprice"=>$price
          );
          if($id=M("red_packets")->add($ary)){
              //开启成功修改红包表状态
              $list=M("red_count")->where(array("id"=>$red_id))->select();
              $list[0]['redcount']=$list[0]['redcount']-1;
              $list[0]['status']=1;
              $list[0]['opentime']=$time;
              M("red_count")->save($list[0]);
              //开启成功添加累加金币
              //获取开启之前的金币
              $mem1=M("member")->where(array("member_id"=>$member_id))->find();
              if($mem=M("member")->where(array("member_id"=>$member_id))->select()){
                  //开启之后的红包金额
                  $mem[0]['coin']=$mem[0]['coin']+$price;
                  M("member")->save($mem[0]);
                  $l=array(
                    "member_id"=>$member_id,
                    "appid"=>$wechat_conif['appid'],
                    "before_coin"=>$mem1['coin'],
                    "after_coin"=>$mem[0]['coin'],
                    "type"=>1,
                    "desc"=>"用户开启红包获得金币".$price."个,ID=".$id,
                    "createtime"=>$time
                  );
                  M("member_coin_detail")->add($l);
              }
              $price_show[] = $price;
          }else{
              echo  json_encode(array("status"=>2,"price"=>$price_show));
          }
        }
        echo  json_encode(array("status"=>1,"price"=>$price_show));
    }else{
        echo  json_encode(array("status"=>3,"msg"=>"请先开始游戏"));
        exit;
    }
  }
  /**
  * 红包计数
  **/
  public function count(){
    if(I("s") != 1){
      echo  json_encode(array("status"=>3,"msg"=>"请先开始游戏"));
      exit;
    }
    $member_id=isset($_SESSION['member_id'])?$_SESSION['member_id']:0;
    $openid=isset($_SESSION['openid'])?$_SESSION['openid']:" ";
    //数量
    $redcount=isset($_POST['num'])?intval($_POST['num']):0;
    //创建时间
    $addtime=time();
    //开启时间
    $opentime=0;
    //1.已经存在 2. 未存在
    if($list=M("red_count")->where(array("member_id"=>$member_id))->select()){//已经存在  修改。。。
      $list[0]['redcount']=$redcount+$list[0]['redcount'];
      M("red_count")->save($list[0]);
        $_SESSION['red_id']=$list[0]['id'];
    }
    else{//未存在 新增.....
      $ary=array(
        "member_id"=>$member_id,
        "openid"=>$openid,
        "redcount"=>$redcount,
        "addtime"=>$addtime
      );
      M("red_count")->add($ary);
      $_SESSION['red_id']=M("red_count")->getLastInsID();
    }

  }
  /**
  *红包列表
  **/
  public function gold(){
    $member_id=isset($_SESSION['member_id'])?$_SESSION['member_id']:0;
    $openid=isset($_SESSION['openid'])?$_SESSION['openid']:" ";
    $ary=M("red_packets")->where(array("member_id"=>$member_id))->order("addtime desc")->select();
    $this->assign("list",$ary);
    $this->display("goldrec");
  }

  /**
  *未开红包
  **/
  public function redWall(){
    $member_id=isset($_SESSION['member_id'])?$_SESSION['member_id']:0;
    $list= M("red_count")->where(array("member_id"=>$member_id))->select();
    $this->assign("list",$list);
    $this->display();
  }
  /**
  *充值金币
  **/
  public function recharge(){

    $distribution_config = M('distribution')->order('id desc')->limit(1)->find();
    $bili = $distribution_config['charge_rmb']/$distribution_config['charge_coin'];
    $this->assign('bili',$bili);
    $this->assign('distribution_config',$distribution_config);
    $this->display();
  }


  public function member_distributions(){
//    F('member',$_SESSION);
    if($_SESSION['member_id']){
      $class = new CoinClasslogic();
      $class :: set_current_mid($_SESSION['member_id']);
      $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
      $class :: get_distribution_config();
      $class :: deal_with_coin();
    }
  }
  public function game_start_cut_coin_ajax(){
      $member_coin = M('member')->where(array('member_id'=>$_SESSION['member_id']))->getField('coin');
      $class = new CoinClasslogic();
      $class :: get_distribution_config();
      $class :: set_games_controller(CONTROLLER_NAME . 'Controller');
      $coin_config = $class :: get_deduction_coin_config();
      $game_config = $class ::get_game_config();
      if($coin_config === null) $coin_config = 0;
      $arr = array(
        'coin_config'=>$coin_config,
        'member_coin'=>$member_coin,
        'games_time'=>$game_config['games_time']*1000
        );
      die(json_encode($arr));
  }

}
