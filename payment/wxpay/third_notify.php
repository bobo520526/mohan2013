<?php
error_reporting(0);
$input = file_get_contents('php://input');
file_put_contents('./input.txt',json_encode($input));
if (!empty($input) && empty($_GET['out_trade_no'])) {
	$obj = simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
	$data = json_decode(json_encode($obj), true);
    file_put_contents('./array_data.txt',json_encode($data));
	if (empty($data)) {
		exit('fail');
	}
	if ($data['result_code'] != 0 || $data['status'] != 0) {
				exit('fail');
	}
	$get = $data;
} else {
	$get = $_GET;
}
if($get){
    $request_data_str = "请求时间 ：" . date("Y-m-d H:i:s",time()) . "\n请求IP：" . $_SERVER["REMOTE_ADDR"];
    $request_data_str .= "请求数据=============\n".json_encode($get) ."\n=====================\n";
    file_put_contents('./request_data_str.txt',$request_data_str,FILE_APPEND);

    $pay_param_str = base64_encode(json_encode($get));
    file_put_contents('./pay_data.txt',$pay_param_str);
//    $url = 'http://' . $_SERVER['HTTP_HOST'] . "/index.php?m=Mobile&c=Payment&a=pay_return&result_data=".$pay_param_str;
    $url = 'http://' . $_SERVER['HTTP_HOST'] . "/index.php/Mobile/Payment/pay_return/result_data/".$pay_param_str;
    file_put_contents('./request_url.txt',$url);
    file_get_contents($url);
    exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $r = curl_exec($ch);
    if($r === false) {
//        file_put_contents('./pay_notify_Curl_error.txt',curl_error($ch)."<br/>\n",FILE_APPEND);
    }
    curl_close($ch);
    exit();
}

exit('fail');
