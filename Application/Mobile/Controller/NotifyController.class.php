<?php
namespace Mobile\Controller;
use Think\Controller;
use Mobile\Classlogic\PaymentClasslogic;

class NotifyController extends Controller {
   public function __construct(){
        parent::__construct();
   }
    
   //统一回调
   public function index(){

        $Pay = new PaymentClasslogic();
        $result = $Pay->fire_notify(I());
        if($result){
            $arr['transaction_id'] = I('tranid');
            $arr['out_trade_no'] = I('ordno');
            $this->change_order($arr);
            //验证签名成功执行
            echo 'ok';
            exit();
        }else{
            echo 'ok';
            exit();
        }

   }
    private function change_order($data){
        if(empty($data) || empty($data['out_trade_no'])){
            exit("ok");
        }
        $order = M('order')->where(array('order_sn'=>$data['out_trade_no']))->find();
        if(!empty($order) && $order['status'] == 0){
            M('order')->where(array('order_sn'=>$data['out_trade_no']))->save(array('status'=>1,'paytime'=>time()));
            $member = M('member')->where(array('member_id'=>$order['member_id']))->find();
            if(!empty($member)){
                M('member')->where(array('member_id'=>$order['member_id']))->save(array('coin'=>$member['coin']+$order['coin']));
            }
        }
    }

    public function buyou_notify(){
        $Pay = new PaymentClasslogic();
        $result = $Pay->buyou_notify(I());
        if($result){
            $arr['transaction_id'] = I('sysnumber');
            $arr['out_trade_no'] = I('ordernumber');
            $arr['total_fee'] = I('paymoney');
            $this->change_order($arr);
            //验证签名成功执行
            echo 'ok';
            exit();
        }else{
            echo 'ok';
            exit();
        }
    }


    public function guofu_notify(){
        $Pay = new PaymentClasslogic();
        $result = $Pay->response($_POST);
        if($result){
            $data = $Pay->gbkToUtf8($_POST);
            $arr['transaction_id'] = $data['channelOrderno'];
            $arr['out_trade_no'] = $data['traceno'];
            $arr['total_fee'] = $data['amount'];
            $this->change_order($_GET['model_type']);
            //验证签名成功执行
            exit('SUCCESS');
        }else{
            exit('SUCCESS');
        }
    }
    
 }   