<?php
namespace Manage\Controller;
use Think\Controller;
use Think\Upload;
class ActivityController extends BaseController {


    public function index(){
        $activity_list = M('activity')->select();
        $this->assign('activity_list',$activity_list);
        $this->display();
    }

    public function pic(){
        $aid = I('get.id',0);
        $activity = M('activity')->where(array('aid'=>$aid))->find();
        $images = explode(",",$activity['images']);
        $this->assign('images',$images);
        $this->display("imegs");
    }

    public function add(){
        $aid = I('aid');
        if($aid){
            $activity = M('activity')->where(array('aid'=>$aid))->find();
            $this->assign('activity',$activity);
        }
        if(IS_POST){
            $flag = array();
            if(!empty($_FILES['file_imgs'])){
                $config = array(
                    "savePath" => "File/Activity/",
                    "maxSize" =>  20000000, // 单位B
                    "exts" => array('jpg', 'gif', 'png', 'jpeg'),
                    "subName" => array('date', 'Y/m-d'),
                );
                $upload = new Upload($config);
                $info   =   $upload->upload(array($_FILES['file_imgs'])); // 上传文件
                if($info) {
                    // 上传成功 获取上传文件信息
                    foreach($info as $file){
                        $flag[] = SITE_URL . "/Uploads/" .$file['savepath'].$file['savename'];
                    }
                }
            }
            if($_FILES['pic_coin']){
                $path = fileHandleImg($_FILES['pic_coin'],"File/Activity/");
                $_POST['pic_coin'] = SITE_URL . "/Uploads/" .$path;
            }
            if(!empty($flag)) $_POST['images'] = implode(",",$flag);
            if(!in_array($_POST['status'],array(1,2))) $this->error("请选择活动状态");
            if(empty($_POST['name'])) $this->error("请填写活动名称");
            if(empty($_POST['title'])) $this->error("请填写分享标题");
            $id = intval($_POST['aid']);
            unset($_POST['aid']);

            if($id){
                M('activity')->where(array('aid'=>$id))->save($_POST);
            }else{
                $_POST['createtime'] = time();
                M('activity')->add($_POST);
            }
            exit($this->success('活动操作成功！',U('index')));
        }
        $this->display();
    }

    public function deleted(){
        $aid = I('aid');
        if(!$aid) $this->error("请求错误！");
        $games = M('activity')->where(array('aid'=>$aid))->find();
        if(empty($games)) $this->error("请求错误，数据不存在！");
        $res = M('activity')->where(array('aid'=>$aid))->delete();
        $res && $this->success("删除成功");
        $this->error("删除失败");
    }



}
