<?php
/**
 * html 提示
 * @param $msg
 * @param bool $is_back
 */
function html_tips($msg = '参数错误',$is_back=true,$url=null,$title = '错误提示'){
    $html = '';
    $html .= '<!doctype html><html><head>';
    $html .= '<meta charset="utf-8">';
    $html .= '<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport">';
    $html .= '<meta http-equiv="Pragma" content="no-cache"> ';
    $html .= '<meta http-equiv="Cache-Control" content="no-cache">';
    $html .= '<meta http-equiv="Expires" content="0" />';
    $html .='<title>'.$title.'</title>';
    $html .= '</head><body></body></html>';
    $html .=' <script src="'.SITE_URL.'/Public/plugins/jQuery/jQuery-2.1.4.min.js"></script>';
    $html .='<script src="'.SITE_URL.'/Public/js/layer/layer.js"></script>';
    $html .='<script language="javascript">';
    $html .='   $(function(){ layer.msg("'.$msg.'");';
    if($is_back) {
        if($url !== null) $html .= 'setTimeout("window.location.href = \"'.$url.'\";",2000)';
        else $html .= 'setTimeout("window.history.go(-1);",2000)';
    }
    $html .='})</script>';
    die($html);
}