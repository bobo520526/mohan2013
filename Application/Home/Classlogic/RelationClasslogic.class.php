<?php
/**
 * 用户关系建立
 * Author: 小强
 */
namespace Home\Classlogic;

use Think\Classlogic;
set_time_limit(0);
ini_set('memory_limit','1024M');

class RelationClasslogic extends Classlogic{
    private static $wechat = array();
    private static $curren_openid;
    private static $parent_openid;
    public function __construct(){
        parent::__construct();
        self::$wechat = M('wechat_config')->where(array('id'=>$_SESSION['account']))->find();

        session('stoken',self::$wechat['token']);
    }

    public static function set_openid($curren_openid,$parent_openid){
        if(!$parent_openid || !$curren_openid) return false;
        self::$curren_openid = $curren_openid;
        self::$parent_openid = $parent_openid;
    }

    /**
     * 改变推荐关系,上级等级也相应改变
     * @param $member_id 当前更换的用户ID
     * @param $member_parent_id 当前用户上级推荐人
     * @return int 状态码:1成功,0参数错误,2当前与上级不能同一人,3上级不能为空,4当前ID在上级路径中
     */
    public static function change($member_id,$member_parent_id) {
        if(!$member_id || !$member_parent_id || $member_id == $member_parent_id || !session('account')) return 0;

        $parent = M('member')->where(array('token'=>self::$wechat ['token'],'member_id'=>$member_parent_id))->find();
        if(!$parent) return 3;

        $current_member = M('member')->where(array('token'=>self::$wechat ['token'],'member_id'=>$member_id))->find();
        if(!$current_member ||  $current_member['member_id'] == $parent['member_id']) return 2;

        $path = self::path2Array($parent['path']);//要更换的上级代理商的上级路径关系
        if(in_array($member_id, $path))return 4;

        M('member')->where(array('token'=>self::$wechat ['token'],'member_id'=>$member_id))->save(array('member_parent_id'=>$member_parent_id));

        //最少量方式,重建当前
        $current_member['member_parent_id'] = $member_parent_id;
        self::generate($current_member);

        //旧关系下所有下级关系路径重建
        $all_members =  M('member')->query("SELECT * FROM g_member WHERE member_id in (SELECT member_id FROM g_member WHERE token = '".self::$wechat ['token']."' AND path like '".$current_member['path']."[".$member_id."]%')");
        if(!empty($all_members)){
            foreach($all_members as $vm) {
                self::generate($vm);
            }
        }
        return 1;
    }


    /**
     * 生成关系
     * @member 当前代理商数组
     * @return model
     */
    public static function generate($member) {
        if(!$member || !$member['member_id'])
            return false;
        $user = new MappingAgent();
        $user->member = $member;
        //组建关系表记录
        $model = array(
            'member_parent_id'=>$member['member_parent_id'],
            'depth'=>0,
            'path'=>'',
        );
        if($model['member_parent_id']>0)
        {
            $model['path'] = '['.$model['member_parent_id'].']';
            $model['depth'] = 1;
            $user->lookup($model);
        }
        M('member')->where(array('token'=>self::$wechat['token'],'member_id'=>$member['member_id']))->save($model);
        return $model;
    }


    /**
     * 返回路径IN查询顺序
     * @param $path 上层路径
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
}



class MappingAgent{
    public $member = array();
    /**
     * 查找上层并统计人数
     * @param array $model 引用对象
     * @return bool
     */
    public function lookup(&$model) {
        $item = M('member')->where(array('member_id'=>$this->member['member_parent_id'],'token'=>$this->member['token']))->find();
        if(!$item || !$item['member_parent_id']) return false;
        $parent = new self;
        $parent->member = $item;
        $model['path'] = '['.$item['member_parent_id'].']'.$model['path'];
        $model['depth']++;
        $parent->lookup($model);
    }


}

