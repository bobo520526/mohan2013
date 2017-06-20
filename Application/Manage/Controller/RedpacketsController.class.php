<?php
namespace Mobile\Controller;
use Think\Controller;
use Home\Classlogic\CommissionClasslogic;
class RedpacketsController extends MobileBaseController {
  /**
  *  抢红包运行。。。。
  **/
  public function run(){
    print_r($_SESSION);exit;
//    $this->display();
  }
  /**
  *红包操作 返回 1.开启金币数量 2.是否开启成功  1 成功 2 失败
  **/
  public function operate(){
    if(I("s") != 1){
      echo  json_encode(array("status"=>3,"msg"=>"请先开始游戏"));
      exit;
    }

    $price=rand(1,10);
    $time=time();
    $member_id=isset($_SESSION['member_id'])?$_SESSION['member_id']:0;
    $openid=isset($_SESSION['openid'])?$_SESSION['openid']:" ";
    $ary=array(
      "member_id"=>$member_id,
      "openid"=>$openid,
      "addtime"=>$time,
      "redprice"=>$price
    );

    if(M("red_packets")->add($ary)){
      echo  json_encode(array("status"=>1,"price"=>$price));
    }
    else{
     echo  json_encode(array("status"=>2,"price"=>$price));
    }


  }

  /**
  *红包列表
  **/
  public function gold(){
    $member_id=isset($_SESSION['member_id'])?$_SESSION['member_id']:0;
    $openid=isset($_SESSION['openid'])?$_SESSION['openid']:" ";
    $ary=M("red_packets")->where(array("member_id"=>$member_id,"openid"=>$openid))->select();
    $this->assign("list",$ary);
    $this->display("goldrec");
  }

  public function member_distributions(){
    F('member',$_SESSION);
    if($_SESSION['member_id']){
      $class = new CommissionClasslogic();
      $class :: set_current_mid($_SESSION['member_id']);
      $class :: deal_with_commission();
    }
  }

}
