<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>个人二维码</title>
		<link rel="stylesheet" type="text/css" href="/Public/index/css/index.css"/>
		<script src="/Public/index/js/rem.js" type="text/javascript" charset="utf-8"></script>
	</head>
	<body>
		<div id="erwei_page">
			<div class="erwei_bg">
				<div class="erwei">
					<img src="<?php echo ($qrcode_link); ?>"/>
				</div>
			</div>
		</div>
	</body>
</html>