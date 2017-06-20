<?php
namespace Manage\Controller;
use Think\Controller;
use Think\Verify;
class PublicController extends BaseController {

    public function index(){
        $this->login();
    }

    /*
     * 管理员登陆
     */
    public function login(){
//       SESSION('admin_id',2);
        if(session('?admin_id') && session('admin_id')>0){
            $this->error("您已登录",U('Manage/Index/index'));
        }

        if(IS_POST){
            $verify = new Verify();
            if (!$verify->check(I('post.vertify'), "admin_login")) {
                exit(json_encode(array('status'=>0,'msg'=>'验证码错误')));
            }
            $condition['admin_name'] = I('post.username');
            $condition['admin_pass'] = I('post.password');
            if(!empty($condition['admin_name']) && !empty($condition['admin_pass'])){
                $condition['admin_pass'] = encryptpwd($condition['admin_pass']);
                $admin_info = M('admin')->where($condition)->find();
                if(is_array($admin_info) && !empty($admin_info)){
                    if(in_array($admin_info['status'],array(0,2))){
                        exit(json_encode(array('status'=>0,'msg'=>'该账号未被启用或已被禁用')));
                    }
                    session('admin_id',$admin_info['admin_id']);
                    $last_login_time = M('admin_log')->where("admin_id = ".$admin_info['admin_id']." and log_info = '后台登录'")->order('log_id desc')->limit(1)->getField('log_time');
                    M('admin')->where("admin_id = ".$admin_info['admin_id'])->save(array('last_login'=>$last_login_time,'last_ip'=>  getIP()));
                    session('last_login_time',$last_login_time);
                    adminLog('后台登录',__ACTION__);
                    $url = session('from_url') ? session('from_url') : U('Manage/Index/index');
                    exit(json_encode(array('status'=>1,'url'=>$url)));
                }else{
                    exit(json_encode(array('status'=>0,'msg'=>'账号密码不正确')));
                }
            }else{
                exit(json_encode(array('status'=>0,'msg'=>'请填写账号密码')));
            }
        }
        $this->display("login");
    }

    /**
     * 退出登陆
     */
    public function logout(){
        session_unset();
        session_destroy();
        $this->success("退出成功",U('Manage/Public/login'));
    }

    /**
     * 验证码获取
     */
    public function vertify()
    {
        $config = array(
            'fontSize' => 30,
            'length' => 4,
            'useCurve' => true,
            'useNoise' => false,
            'reset' => false
        );
        $Verify = new Verify($config);
        $Verify->entry("admin_login");
    }

    public function errfour(){
        $this->display("404");
    }

}