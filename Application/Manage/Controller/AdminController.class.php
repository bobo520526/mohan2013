<?php
namespace Manage\Controller;
use Think\Controller;
class AdminController extends BaseController {



    public function index(){


        $this->display();
    }


    public function edit_password(){
        if(IS_POST){
            if(empty($_POST['old_password'])) $this->error("原始密码不能为空");
            if(empty($_POST['password'])) $this->error("新密码不能为空");
            if(empty($_POST['r_password'])) $this->error("确认新密码不能为空");
            if($_POST['password'] != $_POST['r_password']) $this->error("两次密码不一致");
            if(empty(session('admin_id'))) $this->error("请重新登录在操作");
            $res = M('admin')->where(array('admin_id'=>session('admin_id')))->save(array('admin_pass'=>encryptpwd($_POST['password'])));
            $res && exit($this->success("修改密码成功",U('Index/index')));
            exit($this->error("修改密码失败",U('Index/index')));
        }

        $this->display();
    }

    public function check_password(){
        $old_pass = I('old_pass');
        if(empty($old_pass)){
            die(json_encode(array('state'=>0,'msg'=>'原始密码不能为空')));
        }
        $admin = M('admin')->where(array('admin_id'=>session('admin_id')))->find();
        if(empty($admin)){
            die(json_encode(array('state'=>0,'msg'=>'用户不存在，请重新登录')));
        }
        if($admin['admin_pass'] == encryptpwd($old_pass)){
            die(json_encode(array('state'=>1,'msg'=>'原始密码正确')));
        }
        die(json_encode(array('state'=>0,'msg'=>'原始密码不正确')));
    }

}