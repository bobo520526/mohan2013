<?php
namespace Manage\Controller;
use Think\Controller;
class BaseController extends Controller {

    public function __construct()
    {
        parent::__construct();
    }


    /*
     * 初始化操作
     */
    public function _initialize()
    {
        $this->assign('action',ACTION_NAME);
        //过滤不需要登陆的行为
        if(in_array(ACTION_NAME,array('login','logout','vertify','errfour')) || in_array(CONTROLLER_NAME,array('Ueditor','Uploadify','Public'))){
            //return;
        }else{
            if(session('admin_id') > 0 ){
               // $this->success("您已登录",U('Index/index'));
            }else{
                $this->error('请先登陆',U('Manage/Public/login'),1);
            }
        }
        $this->pub_functs();
    }

    public function pub_functs(){
        if(session('?admin_id') && session('admin_id')>0){
            $str = session('admin_item');
            if($str){
               $admin = unserialize($str);
            }else{
                $admin = M('admin')->where(array('admin_id'=>session('admin_id')))->find();
                session('admin_item',serialize($admin));
            }
            $this->assign('admin',$admin);
        }
    }

}