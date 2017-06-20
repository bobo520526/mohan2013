<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        //header('Location: ' . U('Manage/Public/login'));
        header('Location: ' . U('Mobile/Index/index'));
    }
}