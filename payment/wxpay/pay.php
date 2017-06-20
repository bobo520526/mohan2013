<?php
define('SITE_URL','http://'.$_SERVER['HTTP_HOST']);
$sl = trim($_GET['ps']);
$order = @json_decode(base64_decode($sl), true);
$pay_params = trim($_GET['auth']);
$wOpt = @json_decode(base64_decode($pay_params),true);

$back_url = SITE_URL . "/index.php?m=Mobile&c=Index&a=index";
$fail_url = SITE_URL . "/index.php?m=Mobile&c=Redpackets&a=recharge";

if(empty($order) || empty($wOpt)){
//if(empty($wOpt)){
    $html = '';
    $html .= '<!doctype html><html><head>';
    $html .= '<meta charset="utf-8">';
    $html .= '<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport">';
    $html .='<title>错误提示</title>';
    $html .= '</head><body></body></html>';
    $html .=' <script src="'.SITE_URL.'/Public/plugins/jquery-1.8.2.min.js"></script>';
    $html .='<script src="'.SITE_URL.'/Public/js/layer/layer.js"></script>';
    $html .='<script language="javascript">';
    $html .='   $(function(){ layer.msg("参数有误,不允许支付");';
    $html .= 'setTimeout("window.location.href = \"'.$fail_url.'\";",2000)';
    $html .='})</script>';
    die($html);
}
?>
<!doctype html><html><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport">
<title>页面支付</title>
</head><body></body></html>
<script type="text/javascript">
    document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        WeixinJSBridge.invoke('getBrandWCPayRequest', {
            'appId' : '<?php echo $wOpt['appId'];?>',
            'timeStamp': '<?php echo $wOpt['timeStamp'];?>',
            'nonceStr' : '<?php echo $wOpt['nonceStr'];?>',
            'package' : '<?php echo $wOpt['package'];?>',
            'signType' : '<?php echo $wOpt['signType'];?>',
            'paySign' : '<?php echo $wOpt['paySign'];?>'
        }, function(res) {
            if(res.err_msg == 'get_brand_wcpay_request:ok') {
                location.href='<?php echo $back_url; ?>';
            } else {
                //alert('启动微信支付失败, 请检查你的支付参数. 详细错误为: ' + res.err_msg);
                location.href='<?php echo $fail_url; ?>';
            }
        });
    }, false);
</script>