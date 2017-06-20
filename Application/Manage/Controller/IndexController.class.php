<?php
namespace Manage\Controller;
use Think\Controller;
class IndexController extends BaseController {



    public function index(){

        $start = strtotime(date("Y-m-d",time()));
        $end = $start + 86399;

        $last_start = strtotime(date("Y-m-d",strtotime("-1 day")));
        $last_end = $last_start + 86399;

        //总订单数
        $all_order_count = M('order')->count();
        $this->assign('all_order_count',$all_order_count);
        //今日订单数
        $today_order_where['createtime'] = array('between',array($start,$end));
        $today_order = M('order')->where($today_order_where)->count();
        $this->assign('today_order',$today_order);
        //总用户数量
        $all_member_count = M('member')->count();
        $this->assign('all_member_count',$all_member_count);
        //今日用户数
        $today_member_where['createtime'] = array('between',array($start,$end));
        $today_member = M('member')->where($today_order_where)->count();
        $this->assign('today_member',$today_member);
        //交易成功数
        $all_success_order_count = M('order')->where(array('status'=>1))->count();
        $this->assign('all_success_order_count',$all_success_order_count);
        //今日交易成功数
        $today_success_order_where['createtime'] = array('between',array($start,$end));
        $today_success_order_where['status'] = 1;
        $today_success_order = M('order')->where($today_success_order_where)->count();
        $this->assign('today_success_order',$today_success_order);
        //昨日交易成功数
        $last_success_order_where['createtime'] = array('between',array($last_start,$last_end));
        $last_success_order_where['status'] = 1;
        $last_success_order = M('order')->where($last_success_order_where)->count();
        $this->assign('last_success_order',$last_success_order);

        //对比昨天订单百分比
        $to_p = $today_success_order / $last_success_order * 100;
        $this->assign('to_p',sprintf("%.2f",$to_p));
        //昨日订单数
        $last_order_where['createtime'] = array('between',array($last_start,$last_end));
        $last_order = M('order')->where($last_order_where)->count();
        //对比数
        $all_today = $today_order / $last_order * 100;
        $this->assign('all_today',sprintf("%.2f",$all_today));
        //昨日用户数
        $last_member_where['createtime'] = array('between',array($last_start,$last_end));
        $last_member = M('member')->where($last_member_where)->count();
        $tmember_p = $today_member / $last_member * 100;
        $this->assign('tmember_p',sprintf("%.2f",$tmember_p));


        //总数据对比上周
        $last_week_start  = strtotime(date("Y-m-d",strtotime("-7 day")));
        $last_week_end = $last_week_start + 86399;
        //上周总订单数
        $all_order_count_lastweek = M('order')->where(array('createtime'=>array('between',array($last_week_start,$last_week_end))))->count();
        //上周总用户数
        $all_member_count_lastweek = M('member')->where(array('createtime'=>array('between',array($last_week_start,$last_week_end))))->count();
        //上周交易总数
        $all_success_order_count_lastweek = M('order')->where(array('status'=>1,'createtime'=>array('between',array($last_week_start,$last_week_end))))->count();

        //对比上周总数据
        $last_o_p = $all_order_count / $all_order_count_lastweek * 100;
        $this->assign('last_o_p',sprintf("%.2f",$last_o_p));

        $last_o_m = $all_member_count / $all_member_count_lastweek * 100;
        $this->assign('last_o_m',sprintf("%.2f",$last_o_m));

        $last_o_succ = $all_success_order_count / $all_success_order_count_lastweek * 100;
        $this->assign('last_o_succ',sprintf("%.2f",$last_o_succ));


        $this->display();
    }


    public function icons(){

        $this->display();
    }


}