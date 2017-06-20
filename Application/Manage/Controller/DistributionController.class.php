<?php
namespace Manage\Controller;
use Think\Controller;
class DistributionController extends BaseController {


    public function index(){

        $config = M('distribution')->order('id desc')->limit(1)->find();
        if(!empty($config)){
            $config['ways'] = explode(",",$config['ways']);
            $distrib_pro = explode(",",$config['distrib_pro']);
            $this->assign('distrib_pro',$distrib_pro);
        }
        $this->assign('config',$config);
        if(IS_POST){
            if(is_array($_POST['ways'])) $_POST['ways'] = implode(",",$_POST['ways']);
            if(is_array($_POST['distrib_pro'])) $_POST['distrib_pro'] = implode(",",$_POST['distrib_pro']);
            if(empty($config)){
                $_POST['createtime'] = time();
                M('distribution')->add($_POST);
            }else{
                M('distribution')->where(array('id'=>$config['id']))->save($_POST);
            }
            exit($this->success("操作成功！",U('Distribution/index')));
        }

        $this->display();
    }


}