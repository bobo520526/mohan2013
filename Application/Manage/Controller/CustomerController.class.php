<?php
namespace Manage\Controller;
use Think\Controller;
class CustomerController extends BaseController {



    public function index(){

        $customer_list = M('customer')->select();
        $this->assign('customer_list',$customer_list);
        $this->display();
    }


    public function add(){


        $id = I('id',0);
        $item = M('customer')->where(array('id'=>$id))->find();
        $this->assign('item',$item);
        if(IS_POST){
            if(empty($_POST['kfname'])) $this->error("请填写客服名称");
            if(empty($_POST['worktime'])) $this->error("请填写工作时间");
            if($_FILES['qrcode']){
                $qr = fileHandleImg($_FILES['qrcode'],"File/Customer/");
                if($qr) $_POST['qrcode'] = SITE_URL ."/Uploads/" . $qr;
            }
            $id= intval($_POST['id']);
            if($id){
                $_POST['updatetime'] = time();
                unset($_POST['id']);
                $res = M('customer')->where(array('id'=>$id))->save($_POST);
            }else{
                $_POST['createtime'] = time();
                $res = M('customer')->add($_POST);
            }
            $res && exit($this->success("操作成功",U('Customer/index')));
            exit($this->error("操作失败"));
        }

        $this->display();
    }


    public function deleted(){
        $id = I('id',0);
        if(!$id) $this->error("请求错误");
        $item = M('customer')->where(array('id'=>$id))->find();
        if(empty($item)) $this->error("请求失败，数据不存在");
        M('customer')->where(array('id'=>$id))->delete();
        $this->success("操作成功!");
    }

}