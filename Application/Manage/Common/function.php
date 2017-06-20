<?php
/**
 * 管理员操作记录
 * @param $log_url 操作URL
 * @param $log_info 记录信息
 */
function adminLog($log_info){
    $add['log_time'] = time();
    $add['admin_id'] = session('admin_id');
    $add['log_info'] = $log_info;
    $add['log_ip'] = getIP();
    $add['log_url'] = __ACTION__;
    M('admin_log')->add($add);
}

/**
 * 获取该模块下的所有控制器
 * @param string $model
 * @return array
 */
function getModelController($model = 'Mobile'){
    $planPath = APP_PATH.$model.'/Controller';
    $planList = array();
    $dirRes   = opendir($planPath);
    while($dir = readdir($dirRes))
    {
        if(!in_array($dir,array('.','..','.svn')))
        {
            if($dir == 'index.html') continue;
            if($dir != 'BaseController.class.php'){
                $planList[] = basename($dir,'.class.php');
            }
        }
    }
    return $planList;
}


