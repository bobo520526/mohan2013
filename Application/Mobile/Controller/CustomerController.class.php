<?php
namespace Mobile\Controller;
use Think\Controller;
class CustomerController extends Controller {



    public function index(){

        $customer = M('customer')->order('id desc')->limit(1)->find();
        $this->assign('customer',$customer);
        $this->assign('type',array('1'=>'电话','2'=>'QQ','3'=>'微信','4'=>'二维码'));
        $this->display();
    }



}