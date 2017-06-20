<?php
namespace Manage\Controller;
use Think\Controller;
class DistributionWechatController extends BaseController {


    public function index(){

        $wechat = M('distribution_wechat')->where(array('id'=>1))->find();
        $this->assign('wechat',$wechat);
        if(IS_POST){
            if(empty($_POST['token'])){
                $_POST['token'] = get_rand_str(6,1,0);
            }
            $return_data = fileHandle($_FILES,"File/DistributionWechat/");
            if($return_data){
                if($return_data[0]['apiclient_cert']['savepath'] && $return_data[0]['apiclient_cert']['savename']){
                    $_POST['apiclient_cert'] = $return_data[0]['apiclient_cert']['savepath'] . $return_data[0]['apiclient_cert']['savename'];
                }
                if($return_data[0]['apiclient_key']['savepath'] && $return_data[0]['apiclient_key']['savename']){
                    $_POST['apiclient_key'] = $return_data[0]['apiclient_key']['savepath'] . $return_data[0]['apiclient_key']['savename'];
                }
                if($return_data[0]['rootca_pem']['savepath'] && $return_data[0]['rootca_pem']['savename']){
                    $_POST['rootca_pem'] = $return_data[0]['rootca_pem']['savepath'] . $return_data[0]['rootca_pem']['savename'];
                }
            }

            $id = intval($_POST['id']);
            $model = M('distribution_wechat');
            if(!$id){
                unset($_POST['id']);
                $_POST['admin_id'] = session('admin_id');
                $_POST['createtime'] = time();
                $row = $model->add($_POST);
            }else{
                $_POST['updatetime'] = time();
                $row = $model->where(array('id'=>$id))->save($_POST);
            }
            $row && exit($this->success("操作成功！",U('DistributionWechat/index')));
            exit($this->error("操作失败"));
        }

        $this->display();
    }


}