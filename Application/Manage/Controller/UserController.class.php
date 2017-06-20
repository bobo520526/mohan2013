<?php
namespace Manage\Controller;
use Think\Controller;
class UserController extends Controller {

    public function index(){


        $this->display();
    }

    public function login(){

        $this->display();
    }

    public function icons(){

        $this->display();
    }
    //获取管理员信息
    public function ulist(){

      $m=M("admin");
      $ary=$m->select();
      $this->assign("list",$ary);
     $this->display("list");
    }
    //删除信息
    public function del(){
      $admin_id=I("id");
      $m=M("admin");
      if($m->where("admin_id={$admin_id}")->delete()){
        $this->success("删除成功",U("User/ulist"));
      }
      else{
        $this->error("删除失败");
      }

    }
    //修改
    public function edit(){
      $admin_id=I("id");
      $m=M("admin");
      $res=$m->where("admin_id={$admin_id}")->limit(1)->select();
      if($res){
        $this->assign("list",$res);
        $this->display();

      }
      else{
        $this->error("查询出错");
      }

    }
    public function xedit(){

      $m=M("admin");

      $ary=array(
        "admin_id"=>I("admin_id"),
        "admin_name"=>I("admin_name"),
        "admin_phone"=>I("admin_phone"),
        "true_name"=>I("true_name"),
        "sex"=>I("sex"),
        "status"=>I("status")
      );
      $pas=I("admin_pass");
      if(!empty($pas)){
           $ary['admin_pass']=  encryptpwd($pas);
      }
     if($A=$m->save($ary)){
       $this->success("保存成功",U("User/ulist"));
     }
     else{
       $this->error("修改失败");
     }

    }
    public function xadd(){
      $ary=I();
      $ary['addtime']=time();
      $ary['admin_pass']= encryptpwd(I('admin_pass'));
      $m=M("admin");
     $res=$m->where("admin_name = '{$ary['admin_name']}'")->limit(1)->select();

    if($res){
      $this->error("用户名已存在");
    }
      if($m->add($ary)){
        $this->success("添加成功",U("User/ulist"));
      }
      else{
        $this->error("添加失败");
      }
    }

}
