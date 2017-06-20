<?php
namespace Manage\Controller;
use Think\Controller;
class WithdrawalsController extends BaseController {

    function randFloat($min=0, $max=1){
        return $min + mt_rand()/mt_getrandmax() * ($max-$min);
    }

    public function index(){
        $all = 0;
        for($i=0;$i<15;$i++){
            $price = sprintf('%.2f',($this->randFloat()*3)/15);
            echo "<br />".$price;
            $all += $price;
        }
//        $price = (rand(0.1,2)*3)/15;
//        sprintf("%.2f",$price)
        echo "<br />".sprintf("%.2f",$all);
dd($all);
        $list = M('withdrawal')->select();
        if(!empty($list)){
            foreach($list as $k=>$item){
                $member = M('member')->where(array('member_id'=>$item['member_id']))->find();
                if($member['nickname']) $list[$k]['nickname'] = $member['nickname'];
                else $list[$k]['nickname'] = '未关注用户';
            }
        }
        $this->assign('list',$list);
        $this->assign('status',array('审核中','已通过','已拒绝'));
        $this->display();
    }

    public function pass(){
        $id = I('id');
        if(!$id) $this->error("请求错误");
        $item = M('withdrawal')->where(array('id'=>$id))->find();
        if(empty($item)) $this->error("请求错误，数据不存在");
        $member = M('member')->where(array('member_id'=>$item['member_id']))->find();
        if(empty($member) || empty($member['openid'])) $this->error("请求错误，用户不存在");
        //才看分销配置，调用接口操作
        $this->success("审核成功！");
    }

    public function nopass(){
        $id = I('id');
        if(!$id) $this->error("请求错误");
        $item = M('withdrawal')->where(array('id'=>$id))->find();
        if(empty($item)) $this->error("请求错误，数据不存在");
        $member = M('member')->where(array('member_id'=>$item['member_id']))->find();
        if(empty($member) || empty($member['openid'])) $this->error("请求错误，用户不存在");
        M('withdrawal')->where(array('id'=>$id))->save(array('apply_status'=>2));
        M('member')->where(array('member_id'=>$item['member_id']))->save(array('wallet'=>$member['wallet']+$item['apply_cash']));
        $wallet_detail = array(
            'member_id'=>$item['member_id']['member_id'],
            'before_wallet'=>$member['wallet'],
            'after_wallet'=>$member['wallet']+$item['apply_cash'],
            'desc'=>'审核拒绝资金返回 id:'.$id,
            'createtime'=>time(),
        );
        M('member_wallet_detail')->add($wallet_detail);
        $this->success("拒绝成功！");
    }
}