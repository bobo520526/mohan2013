<?php
namespace Manage\Controller;
use Think\Controller;
class GamesController extends BaseController {


    public function index(){
        $games_list = M('games')->select();
        $this->assign('games_list',$games_list);
        $this->display();
    }

    public function add(){
        $gid = I('gid');
        if($gid){
            $games = M('games')->where(array('gid'=>$gid))->find();
            $this->assign('games',$games);
        }
        if(IS_POST){
            // 雷振兴开始
            $data = array();
            if(!empty($_FILES['game_logo']['name'])){
                $config = array(   
                    'maxSize'    =>    3145728, 
                    'savePath'   =>    '',  
                    'saveName'   =>    array('uniqid',''), 
                    'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
                    'autoSub'    =>    true,   
                    'subName'    =>    array('date','Ymd'),
                );
                $upload = new \Think\Upload($config);// 实例化上传类
                $info = $upload->upload($_FILES);
                if($info) {
                    // 上传成功 获取上传文件信息
                    $data['game_logo'] = "Uploads/" .$info['game_logo']['savepath'].$info['game_logo']['savename'];
                }
            }
            // 雷振兴结束
            
            if(!in_array($_POST['status'],array(1,2))) $this->error("请选择游戏状态");
            if(empty($_POST['controllers'])) $this->error("请选择游戏的控制器");
            $id = intval($_POST['gid']);
            // 雷振兴
            $data['gname'] = $_POST['gname'];
            $data['controllers'] = $_POST['controllers'];
            $data['status'] = $_POST['status'];
            $data['url'] = get_controllers_name($data['controllers']);  //获取控制器名
            // 雷振兴结束兴
            if($id){
                unset($_POST['gid']);
                M('games')->where(array('gid'=>$id))->save($data);
            }else{
                $data['createtime'] = time();
                M('games')->add($data);
            }
            exit($this->success('添加游戏成功！',U('index')));
        }
        $this->display();
    }

    public function deleted(){
        $gid = I('gid');
        if(!$gid) $this->error("请求错误！");
        $games = M('games')->where(array('gid'=>$gid))->find();
        if(empty($games)) $this->error("请求错误，游戏数据不存在！");
        $res = M('games')->where(array('gid'=>$gid))->delete();
        $res && $this->success("删除成功");
        $this->error("删除失败");
    }


    public function config(){

        $deduction_coin = M('distribution')->getField('deduction_coin');
        $this->assign('deduction_coin',$deduction_coin);
        $gid = I('get.gid');
        if(!$gid) $this->error("请求错误！");
        $item = M('games')->where(array('gid'=>$gid))->find();
        if(empty($item)) $this->error("请求错误，游戏不存在！");
        $config = M('games_config')->where(array('gid'=>$gid))->find();
        if(!empty($config)){
            if(!empty($config['other_coins'])){
                $html = '';
                $other_rule = explode(',',$config['other_coins']);
                foreach($other_rule as $k=>$other){
                    $number = $k+1;
                    $html .= '<div class="item form-group othersrules">';
                    $html .= '<label class="control-label col-md-3 col-sm-3 col-xs-12">规则'.$number.'： <span class="required">*</span></label>';
                    $html .= '<div class="col-md-6 col-sm-6 col-xs-12 input-group">';
                    $html .= '<input type="number" name="other_coins[]" value="'.$other.'" min="0" placeholder="游戏币数" required="required" class="form-control col-md-7 col-xs-12">';
                    $html .= '<span class="input-group-btn"><button class="btn btn-danger" type="button" onclick="remove_parents(this)">删除</button></span>';
                    $html .= '</div></div>';
                }
                $config['html'] = $html;
                $config['other_coins_number'] = $number+1;
            }
            if(!empty($config['other_probability'])){
                $html = '';
                $other_probability = explode(',',$config['other_probability']);
                foreach($other_probability as $k2=>$probability){
                    $number2 = $k2+1;
                    $html .= '<div class="item form-group othersrules">';
                    $html .= '<label class="control-label col-md-3 col-sm-3 col-xs-12">概率设置'.$number2.'： <span class="required">*</span></label>';
                    $html .= '<div class="col-md-6 col-sm-6 col-xs-12 input-group">';
                    $html .= '<input type="text" name="other_probability[]" value="'.$probability.'" min="0" placeholder="概率设置，0-100" required="required" class="form-control col-md-7 col-xs-12">';
                    $html .= '<span class="input-group-btn"><button class="btn btn-danger" type="button" onclick="remove_parents(this)">删除</button></span>';
                    $html .= '</div></div>';
                }
                $config['other_probability_html'] = $html;
                $config['other_probability_number'] = $number2+1;
            }

//            if(!empty($config['other_generate_min_coin']) && !empty($config['other_generate_max_coin'])){
//                $other_generate_min_coin = explode(",",$config['other_generate_min_coin']);
//                $other_generate_max_coin = explode(",",$config['other_generate_max_coin']);
//                $html = '';
//                foreach($other_generate_max_coin as $k=>$max){
//                    $number = $k+1;
//                    $html .= '<div class="item form-group othersrules">';
//                    $html .= '<label class="control-label col-md-3 col-sm-3 col-xs-12">规则'.$number.'： <span class="required">*</span></label>';
//                    $html .= '<div class="col-md-6 col-sm-6 col-xs-12 input-group">';
//                    $html .= '<input type="text" name="other_generate_min_coin[]" value="'.$other_generate_min_coin[$k].'" required="required" min="0" placeholder="最小金币" class="form-control col-md-3 col-xs-3">';
//                    $html .= '<span class="input-group-btn"><button class="btn btn-dark" type="button">到</button></span>';
//                    $html .= '<input style="margin-left: -5px;" type="text" name="other_generate_max_coin[]" value="'.$max.'" required="required" min="0" placeholder="最大金币" class="form-control col-md-3 col-xs-3">';
//                    $html .= '<span class="input-group-btn"><button class="btn btn-danger" type="button" onclick="remove_parents(this)">删除</button></span></div></div>';

//                }
//                $config['html'] = $html;
//            }

        }
        $this->assign('config',$config);
        $this->assign('games',$item);
        if(IS_POST){
            $_POST['gid'] = $gid;
            $_POST['system_deduction_coin'] = $deduction_coin;

            $_POST['other_generate_min_coin'] = implode(",",$_POST['other_generate_min_coin']);
            $_POST['other_generate_max_coin'] = implode(",",$_POST['other_generate_max_coin']);
            $_POST['other_coins'] = implode(",",$_POST['other_coins']);
            $_POST['other_probability'] = implode(",",$_POST['other_probability']);
            if(intval($_POST['probability']) > 100) $_POST['probability'] = 100;
            if(intval($_POST['probability']) < 0) $_POST['probability'] = 0;

            if(empty($config)){
                M('games_config')->add($_POST);
            }else{
                M('games_config')->where(array('gid'=>$gid))->save($_POST);
            }
            exit($this->success("游戏配置成功！",U('Games/index')));
        }

        $this->display();
    }

    /**
     * ajax 获取所属模块下的控制器
     */
    public function ajax_get_controller(){
        $model = I('model');
        $id = I('id');
        if($id)
            $item = M('games')->where(array('gid'=>$id))->find();
        $controller_list = getModelController($model);
        $html = '<option value="">选择控制器</option>';
        if(!empty($controller_list)){
            foreach($controller_list as $controller){
                if(!empty($item['controllers']) && $item['controllers'] == $controller){
                    $html .= "<option selected value='".$controller."'>".$controller."</option>";
                }else
                    $html .= "<option value='".$controller."'>".$controller."</option>";
            }
        }
        exit($html);
    }


}
