<?php
/**
 * 金币运行
 * Author: 小强
 */
namespace Mobile\Classlogic;

use Think\Classlogic;
set_time_limit(0);
ini_set('memory_limit','1024M');

class CoinClasslogic extends Classlogic{
    private static $current_member = array();
    private static $current_member_id;
    private static $current_openid;
    private static $parent_member = array();
    private static $parent_openid;
    private static $parent_member_id;
    private static $distribution_config = array();
    private static $games_controller;

    public function __construct(){
        parent::__construct();

    }

    public static function set_games_controller($controllert){
        if($controllert) self::$games_controller = $controllert;
    }

    public static function set_current_mid($member_id){
        if(empty($member_id)) return false;
        $current_member = M('member')->where(array('member_id'=>$member_id))->find();
        if(!empty($current_member)){
            self::$current_member = $current_member;
            self::$current_member_id = $member_id;
            self::$current_openid = $current_member['openid'];
            if(self::$current_member['member_parent_id']){
//                self::get_parent(self::$current_member['member_parent_id']);
            }
        }
    }

    public static function get_parent($member_parent_id){
        if(!$member_parent_id) return false;
        $parent_member =  M('member')->where(array('member_id'=>$member_parent_id))->find();
        if(!empty($parent_member)){
            self::$parent_member = $parent_member;
            self::$parent_member_id = $member_parent_id;
            self::$parent_openid = $parent_member['openid'];
        }
    }

    /**
     * 返回路径IN查询顺序
     * @param $path 代理商上层路径
     * @param $reverse 0不反转,1反转,即关系[20][21][24][47], 20推21推24推47再推当前粉丝,不反转数组array(20,21,24,47),反转即将数组array(47,24,21,20)
     * @return array
     */
    public static function path2Array($path,$reverse=1)
    {
        if(!$path)return array();
        $arr = json_decode(str_replace('][',',',$path));
        if($reverse)
            return array_reverse($arr);
        else
            return $arr;
    }

    /**
     * 获取分销设置
     */
    public static function get_distribution_config(){
        $config = M('distribution')->order('id desc')->limit(1)->find();
        if(!empty($config)) self::$distribution_config = $config;
    }

    /**
     * 处理佣金信息
     */
    public static function deal_with_commission(){
        return;
        self::get_distribution_config();
        $parent_member_ids = self :: get_parent_length();
        $distrib_pro = explode(",",self::$distribution_config['distrib_pro']);
        $model = M('member');
        if(self::$distribution_config['distribution_cash'] > 0){
            //当前用户的佣金处理
            self::calculation_current_member();
            foreach($parent_member_ids as $k=>$p){
                $commission = (self::$distribution_config['distribution_cash'] * $distrib_pro[$k]) / 100;
                if($commission > 0){
                    $item = $model->where(array('member_id'=>$p))->find();
                    $model->where(array('member_id'=>$p))->save(array('wallet'=>$item['wallet']+$commission));
                    $data = array(
                        'current_member_id'=>self::$current_member_id,
                        'parent_member_id'=>$p,
                        'proportion'=>self::$distribution_config['distributionval'],
                        'commission'=>$commission,
                        'distribution_cash'=>self::$distribution_config['distribution_cash'],
                        'createtime'=>time(),
                    );
                    self::commission_log($data);
                    unset($data);
                }
            }
        }
    }

    public static function deal_with_coin(){
        $parent_member_ids = self :: get_parent_length();
        $distrib_pro = explode(",",self::$distribution_config['distrib_pro']);
        $model = M('member');
        $deduction_coin = self :: get_deduction_coin_config();
        if($deduction_coin > 0){
            foreach($parent_member_ids as $k=>$p){
                $coin = ($deduction_coin * $distrib_pro[$k]) / 100;
                if($coin > 0){
                    $item = $model->where(array('member_id'=>$p))->find();
                    $model->where(array('member_id'=>$p))->save(array('coin'=>$item['coin']+$coin));
                    $data = array(
                        'current_member_id'=>self::$current_member_id,
                        'parent_member_id'=>$p,
                        'proportion'=>self::$distribution_config['distributionval'],
                        'coin'=>$coin,
                        'distribution_coin'=>$deduction_coin,
                        'createtime'=>time(),
                    );
                    self::coin_log($data);
                    unset($data);
                }
            }
        }
    }

    /**
     * 获取分销设置的级别以及符合当前用户的上级
     * @return array
     */
    public static function get_parent_length(){
        $new_path = array();
        $level = self::$distribution_config['distributionval'];
        $path = self::path2Array(self::$current_member['path']);
        if($level < count($path)){
            foreach($path as $k=>$v){
                if($k < $level) $new_path []= $v;
            }
        }else{
            $new_path = $path;
        }
        return $new_path;
    }

    /**
     * 扣除当前用户金币
     */
    public static function calculation_coin_current_member(){
        $deduction_coin = self :: get_deduction_coin_config();
        if($deduction_coin === null) return false;
        $surplus_coin = self::$current_member['coin'] - $deduction_coin;
        // return $surplus_coin;
        if($surplus_coin < 0) return false;
        $res = M('member')->where(array('member_id'=>self::$current_member_id))->save(array('coin'=>$surplus_coin));
        if($res!==false) return true;
        else return false;
    }


    /**
     * 查看是否有游戏的配置并返回扣除金币数
     * @return mixed
     */
    public static function get_deduction_coin_config(){
        $deduction_coin = null;
        $deduction_coin = self::$distribution_config['deduction_coin'];
        $games = M('games')->where(array('controllers'=>self::$games_controller,'status'=>1))->find();
        if($games) {
            $config = M('games_config')->where(array('gid'=>$games['gid']))->find();
            $deduction_coin = empty($config['min_coin'])?$deduction_coin:$config['min_coin'];
        }
        return $deduction_coin;
    }
    /**
    *获取游戏配置
    **/
    public static function get_game_config(){
          $games = M('games')->where(array('controllers'=>self::$games_controller,'status'=>1))->find();
          $config=array();
          if($games){
              $config = M('games_config')->where(array('gid'=>$games['gid']))->find();
              $config['game']=$games;
          }
          return $config;
    }

    public static function get_coin_generate_rule(){
        $games = M('games')->where(array('controllers'=>self::$games_controller,'status'=>1))->find();
        if($games) {
            $config = M('games_config')->where(array('gid'=>$games['gid']))->find();
            return self :: randomFloat($config['generate_min_coin'],$config['generate_max_coin']);
        }
        return  self :: rand(0.1,2);
    }

    public static function randomFloat($min = 0, $max = 1) {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    /**
     * 当前用户的佣金处理
     */
    public static function calculation_current_member(){
        return;
        $commission = (self::$distribution_config['distribution_cash'] * self::$distribution_config['current_member_pro']) / 100;
        if($commission > 0){
            $item = M('member')->where(array('member_id'=>self::$current_member_id))->find();
            M('member')->where(array('member_id'=>self::$current_member_id))->save(array('wallet'=>$item['wallet']+$commission));
            $data = array(
                'current_member_id'=>self::$current_member_id,
                'parent_member_id'=>self::$current_member_id,
                'proportion'=>self::$distribution_config['distributionval'],
                'commission'=>$commission,
                'distribution_cash'=>self::$distribution_config['distribution_cash'],
                'createtime'=>time(),
            );
            self::commission_log($data);
        }
    }

    /**
     * 佣金记录明细
     * @param $data
     */
    public static function commission_log($data){
        M('commission_log')->add($data);
    }

    public static function coin_log($data){
        M('coin_log')->add($data);
    }

}
