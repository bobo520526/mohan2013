<?php
namespace Manage\Controller;
use Think\Controller;
use Home\Classlogic\Weixin_accountClasslogic;
class WechatController extends BaseController {

    /**
     * 微信配置
     */
    public function index(){

        $wechat_list = M('wechat_config')->select();
        $this->assign('wechat_list',$wechat_list);
        $this->display();
    }

    public function config(){
        $id = I('id');
        $wechat = M('wechat_config')->where(array('id'=>$id))->find();
        $this->assign('wechat',$wechat);
        if(IS_POST){
            if(empty($_POST['token'])){
                $_POST['token'] = get_rand_str(6,1,0);
            }
            if($_FILES['wx_qrcode']){
                $qr = fileHandleImg($_FILES['wx_qrcode']);
                if($qr) $_POST['wx_qrcode'] = $qr;
            }
            
            $return_data = fileHandle($_FILES,"File/Wechat/".$_POST['token']."/");
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
            $model = M('wechat_config');
            if(!$id){
                unset($_POST['id']);
                $_POST['admin_id'] = session('admin_id');
                $_POST['createtime'] = time();
                $row = $model->add($_POST);
            }else{
                $_POST['updatetime'] = time();
                $row = $model->where(array('id'=>$id))->save($_POST);
            }
            $row && exit($this->success("操作成功！",U('Wechat/index')));
            exit($this->error("操作失败"));
        }
        if(!$id){
            $last = M('wechat_config')->order('id desc')->limit(1)->find();
            if(empty($last)){
                $query=M()->query("SHOW TABLE STATUS LIKE 'g_wechat_config'");
                $id = $query[0]['auto_increment'];
            }else{
                $id = $last['id'] + 1;
            }
        }
        $apiurl = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?m=Home&c=Weixin&a=index&id='.$id;
        $this->assign('apiurl',$apiurl);
        $this->display();
    }

    public function deleted(){
        $id = I('get.id',0);
        if(!$id) $this->error("请求失败");
        $item = M('wechat_config')->where(array('id'=>$id))->find();
        if(empty($item)) $this->error("请求失败，数据不存在！");
        M('wechat_config')->where(array('id'=>$id))->delete();
        $this->success("删除成功！");
    }

    /**
     * 自定义菜单
     */
     public function menu(){


       $tree     = array();
     $packData = array();
     $datas=M("wechat_menu")->order("sort asc")->select();

     /*
     foreach ($datas as $data) {
       if($data['pid'] == 0){
         $tree[]=$data;
       }
     }
     $a=0;
     foreach($tree as $t){
       foreach($datas as $d){
         if($d['pid']==$t['id']){
           $tree[$a]['zi'][]=$d;
         }
       }
       $a+=1;
     }*/
     foreach($datas as $k=>$y){
       if($y['pid'] == 0){
           $a=M("wechat_menu")->where("pid={$y['id']}")->order("sort asc")->count();
           if($a>0){
             $datas[$k]['value']="";
             $datas[$k]['pid']="";
             $datas[$k]['type']="";
           }
           else{

               $datas[$k]['pid']="";

             switch ($y['type']) {
               case 'click':
                 $datas[$k]['type']="点击事件";
                 break;
                 case 'view':
                   $datas[$k]['type']="跳转URL";
                   break;
                   case 'scancode_push':
                     $datas[$k]['type']="扫码推事件";
                     break;
                     case 'scancode_waitmsg':
                       $datas[$k]['type']="提示框";
                       break;
                       case 'pic_sysphoto':
                         $datas[$k]['type']="弹出系统拍照发图";
                         break;
                         case 'pic_photo_or_album':
                           $datas[$k]['type']="弹出拍照或者相册发图";
                           break;
                           case 'pic_weixin':
                           $datas[$k]['type']="弹出微信相册发图器";
                             break;
                             case 'location_select':
                               $datas[$k]['type']="弹出地理位置选择器";
                               break;
             }

           }

       }
       else{
         $ary=M("wechat_menu")->where("id={$y['pid']}")->limit(1)->select();
         $datas[$k]['pid']=$ary[0]['name'];
         switch ($y['type']) {
           case 'click':
             $datas[$k]['type']="点击事件";
             break;
             case 'view':
               $datas[$k]['type']="跳转URL";
               break;
               case 'scancode_push':
                 $datas[$k]['type']="扫码推事件";
                 break;
                 case 'scancode_waitmsg':
                   $datas[$k]['type']="提示框";
                   break;
                   case 'pic_sysphoto':
                     $datas[$k]['type']="弹出系统拍照发图";
                     break;
                     case 'pic_photo_or_album':
                       $datas[$k]['type']="弹出拍照或者相册发图";
                       break;
                       case 'pic_weixin':
                       $datas[$k]['type']="弹出微信相册发图器";
                         break;
                         case 'location_select':
                           $datas[$k]['type']="弹出地理位置选择器";
                           break;
         }
       }

     }

     $this->assign("list",$datas);
     $this->display();


     }


     /**
     * 添加自定义菜单
     */
     public function addlist(){
       //查询所有菜单
       $res=M("wechat_menu")->where("pid=0")->field("id,name")->select();
       $this->assign("list",$res);
       $this->display();
    }

    public function add(){
      $ary=I();
      $ary['admin_id']=0;
      $a=M("wechat_menu")->where("pid={$ary['pid']}")->count();
      if($ary['pid']==0 && $a == 3){
        $this->error("父菜单只能添加三个");
      }
      else if($ary['pid']!=0 && $a==5){
        $this->error("子菜单只能添加五个");
      }

      if(M("wechat_menu")->add($ary)){
          $this->success("添加成功",U("Wechat/menu"));
      }
      else{
          $this->error("失败");
      }
    }
    /**
     * 删除自定义菜单
     */
    public function del(){
      $id=I("id");

    if(M("wechat_menu")->where("id={$id}")->delete()){
      M("wechat_menu")->where("pid={$id}")->delete();
      $this->success("删除成功",U("Wechat/menu"));
    }
    else{
      $this->error("删除失败");
    }
    }
    /**
    *修改自定义菜单
    */
    public function edit(){
      $id=I("id");
      $ary=M("wechat_menu")->where("id={$id}")->select();
      //查询所有菜单
      $res=M("wechat_menu")->where("pid=0")->field("id,name")->select();
      $this->assign("list1",$res);
      $this->assign("list",$ary);
      $this->display();

    }
    public function xedit(){
      $ary=I();
      if($ary['type']== "click" || $ary['type']=="view")
      {
        $ary['value']=I("value");
      }
      else{
          $ary['value']=" ";
      }
      /*
        $count1=M("wechat_menu")->where("pid=0")->count();
        if($count1 == 3){
          $this->error("主菜单只能添加3个");
        }
    $count=M("wechat_menu")->where("pid={$pid}")->count();
    if($count == 5){
      $this->error("子菜单只能设置5个");
    }*/
$a=M("wechat_menu")->save($ary);
    if($a){
      $this->success("修改成功",U("Wechat/menu"));
    }
    else{
      $this->error("修改失败");
    }



    }



    //菜单转换
    private function convert_menu($p_menus){
        $new_arr = array();
        $count = 0;
        foreach($p_menus as $k => $v){
            $new_arr[$count]['name'] = $v['name'];
            //获取子菜单
            $c_menus = M('wechat_menu')->where(array('pid'=>$k))->select();
            if($c_menus){
                foreach($c_menus as $kk=>$vv){
                    $add = array();
                    $add['name'] = $vv['name'];
                    $add['type'] = $vv['type'];
                    // click类型
                    if($add['type'] == 'click'){
                        $add['key'] = $vv['value'];
                    }elseif($add['type'] == 'view'){
                        $add['url'] = $vv['value'];
                    }elseif($add['type'] == 'flow'){
                        $add['type'] = 'view';
                        $add['url'] = $vv['value'];
                    }else{
                        //$add['key'] = $key_map[$add['type']];
                        $add['key'] = $vv['value'];
                    }
                    $add['sub_button'] = array();
                    if($add['name']){
                        $new_arr[$count]['sub_button'][] = $add;
                    }
                }
            }else{
                $new_arr[$count]['type'] = $v['type'];
                // click类型
                if($new_arr[$count]['type'] == 'click'){
                    $new_arr[$count]['key'] = $v['value'];
                }elseif($new_arr[$count]['type'] == 'view'){
                    //跳转URL类型
                    $new_arr[$count]['url'] = $v['value'];
                }elseif($new_arr[$count]['type'] == 'flow'){
                    $new_arr[$count]['type'] = 'view';
                    $new_arr[$count]['url'] = $v['value'];
                }else{
                    //其他事件类型
                    $new_arr[$count]['key'] = $v['value'];
                }
            }
            $count++;
        }
        return json_encode(array('button'=>$new_arr),JSON_UNESCAPED_UNICODE);
    }


    /**
     * 发布菜单
     */
    public function creat_menu(){
            //获取父级菜单
            $p_menus = M('wechat_menu')->where(array('pid'=>0))->order('id ASC')->select();
            $p_menus = convert_arr_key($p_menus,'id');

            $post_str = $this->convert_menu($p_menus);

            // http post请求
            if(!count($p_menus) > 0){
                $this->error('没有菜单可发布',U('Wechat/menu'));
                exit;
            }

            $wechat = M('wechat_config')->where(array('is_default'=>1))->find();
            if(!empty($wechat)){
                $class = new Weixin_accountClasslogic($wechat['id']);
                $access_token = $class->get_access_token();
                if(!$access_token){
                    $this->error('获取access_token失败',U('Wechat/menu'));
                    exit;
                }
                $url ="https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
                $return = httpRequest($url,'POST',$post_str);
                $return = json_decode($return,1);
                if($return['errcode'] == 0){
                    $this->success('菜单已成功生成',U('Wechat/menu'));
                }else{
                    exit($this->error("错误代码;".$return['errcode'],U('Wechat/menu')));
                }
            }

    }

    /**
     * 自定义回复 【文本回复，图文回复】
     */
    public function reply(){
        $keyword_list = M('keyword')->select();
        $this->assign('keyword_list',$keyword_list);
        $this->assign('type',array('text'=>'文本回复','img'=>'图文回复'));
        $this->display();
    }

    public function reply_edit(){
        $kid = I('id',0);
        if(!$kid) $this->error("请求错误");
        $item = M('keyword')->where(array('id'=>$kid))->find();
        if(empty($item)) $this->error("请求错误，数据不存在");
        $this->assign("item",$item);
        if($item['type'] == 'text'){
            $this->display("text");
        }
        if($item['type'] == 'img'){
            $this->display("img");
        }
    }

    public function reply_deleted(){
        $kid = I('id',0);
        if(!$kid) $this->error("请求错误");
        $item = M('keyword')->where(array('id'=>$kid))->find();
        if(empty($item)) $this->error("请求错误，数据不存在");
        M('keyword')->where(array('id'=>$kid))->delete();
        $this->success("删除成功！");
    }

    public function text(){
        if(IS_POST){
            if(empty($_POST['keyword'])) $this->error("关键词不能为空");
            if(empty($_POST['content'])) $this->error("回复内容不能为空");
            $id = intval($_POST['id']);
            if($id){
                $res = M('keyword')->where(array('id'=>$id))->save($_POST);
            }else{
                $_POST['createtime'] = time();
                $res = M('keyword')->add($_POST);
            }
            $res && exit($this->success("操作成功！",U('Wechat/reply')));
            exit($this->error("操作失败！",U('Wechat/reply')));
        }
        $this->display();
    }

    public function img(){

        if(IS_POST) {
            $return_data = fileHandleImg($_FILES, "File/Wechat/Keyword/");
            if ($return_data) {
                if ($return_data[0]['img']['savepath'] && $return_data[0]['img']['savename']) {
                    $_POST['img'] = $return_data[0]['img']['savepath'] . $return_data[0]['img']['savename'];
                }
            }
            if(empty($_POST['keyword'])) $this->error("关键词不能为空");
            if(empty($_POST['title'])) $this->error("图文标题不能为空");
            if(empty($_POST['url'])) $this->error("图文链接不能为空");
            if(empty($_POST['content'])) $this->error("内容简介不能为空");
            $id = intval($_POST['id']);
            if($id){
                $res = M('keyword')->where(array('id'=>$id))->save($_POST);
            }else{
                $_POST['createtime'] = time();
                $res = M('keyword')->add($_POST);
            }
            $res && exit($this->success("操作成功！",U('Wechat/reply')));
            exit($this->error("操作失败！",U('Wechat/reply')));
        }

        $this->display();
    }

    /**
     * 微信用户管理
     */
    public function users(){
        $wechat = M('wechat_config')->where(array('is_default'=>1))->find();
      $ary=M("member")->where(array('token'=>$wechat['token']))->select();
      $this->assign("list",$ary);
      $this->display("ulist");

    }

    public function synch_member(){
        $member_id = I('mid');
        if(!$member_id) {
            die(json_encode(array('state'=>0,'msg'=>'请求错误！')));
        }
        $member = M('member')->where(array('member_id'=>$member_id))->find();
        if(empty($member) || empty($member['openid'])) {
            die(json_encode(array('state'=>0,'msg'=>'同步失败，用户不存在！')));
        }
        $wechat = M('wechat_config')->where(array('is_default'=>1))->find();
        if(empty($wechat)){
            die(json_encode(array('state'=>0,'msg'=>'微信配置错误！')));
        }

        vendor("phpemoji.emoji");

        $class = new Weixin_accountClasslogic($wechat['id']);
        $fans = $class->fansQueryInfo($member['openid']);
        $fans['nickname'] = emoji_unified_to_html($fans['nickname']);
        $update = array(
            'subscribe'=>$fans['subscribe'],
            'nickname'=>$fans['nickname'],
            'head_pic'=>$fans['headimgurl'],
            'country'=>$fans['country'],
            'city'=>$fans['city'],
            'province'=>$fans['province'],
            'updatetime'=>time(),
            'unionid'=>$fans['unionid'],
        );
        if($fans['subscribe']) $update['subscribe_time'] = $fans['subscribe_time'];
        else $update['unsubscribe_time'] = $fans['subscribe_time'];
        M('member')->where(array('member_id'=>$member_id))->save($update);
        if($update['subscribe']){
            $return = array(
                'state'=>1,
                'msg'=>'更新成功',
                'subscribe'=>'已关注',
                'nickname'=>$update['nickname'],
                'head_pic'=>$update['head_pic'],
                'subscribe_time'=>date("Y-m-d H:i:s",$update['subscribe_time']),
            );
        }else{
            $return = array(
                'state'=>2,
                'msg'=>'更新成功',
                'subscribe'=>'已取消',
                'unsubscribe_time'=>date("Y-m-d H:i:s",$update['subscribe_time']),
            );
        }
        die(json_encode($return));
    }

    public function user_detail(){
        $id = I('member_id',0);
        if(!$id) $this->error("请求错误！");
        $member = M('member')->where(array('member_id'=>$id))->find();
        if(empty($member)) $this->error("用户不存在！");
        $this->assign('member',$member);
        $this->display();
    }

}
