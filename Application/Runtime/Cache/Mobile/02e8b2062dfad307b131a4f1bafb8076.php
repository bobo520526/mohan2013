<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>个人中心</title>
		<link rel="stylesheet" type="text/css" href="/Public/index/css/index.css"/>
		<script src="/Public/js/jquery-1.8.2.min.js" type="text/javascript"></script>
		<script src="/Public/index/js/rem.js" type="text/javascript" charset="utf-8"></script>
		<script src="/Public/index/js/index.js" type="text/javascript" charset="utf-8"></script>
		<script src="/Public/js/layer/layer.js" type="text/javascript"></script>
		<link href="/Public/plugins/php-emoji-master/emoji.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="personal">
			<div class="personal_head">
				<div class="personal_pic">
					<?php if($member['head_pic'] == null): ?><img src="/Public/images/defaule_header.png"/>
						<?php else: ?>
						<img src="<?php echo ($member["head_pic"]); ?>"/><?php endif; ?>
				</div>
				<div class="personal_inf">
					<div class="personal_name" id="name"><?php echo ($nickname); ?></div>
					<div class="personal_id" id="num"><?php echo ($member_id); ?></div>
					<!--<input type="text" name="name" class="personal_name" id="name" value="<?php echo ($nickname); ?>" readonly/>-->
					<!--<input type="number" name="num" class="personal_id" id="num" value="<?php echo ($member_id); ?>" readonly/>-->
				</div>
			</div>
			<div class="personal_nav">
				<dl>
					<dt>佣金总额</dt>
					<dd><?php echo ($my_commission); ?>元</dd>
				</dl>
				<dl>
					<dt>已提金额</dt>
					<dd><?php echo ($my_withdrawals); ?>元</dd>
				</dl>
				<dl>
					<dt>账户余额</dt>
					<dd><?php echo ((isset($member["wallet"]) && ($member["wallet"] !== ""))?($member["wallet"]):"0"); ?>元</dd>
				</dl>
			</div>
			<div class="personal_cont">
				<ul class="personal_list">
					<li onclick='window.location.href="<?php echo U('Redpackets/gold');?>"'>累计金币：<span><?php echo ($redcount); ?>个</span><a href="<?php echo U('Redpackets/gold');?>"><img src="/Public/index/img/personal_btn.png"/></a></li>
					<!--<li onclick='window.location.href="<?php echo U('Index/withdrawals');?>"'>可提现余额：<span><?php echo ((isset($member["wallet"]) && ($member["wallet"] !== ""))?($member["wallet"]):"0"); ?>元</span><a href="<?php echo U('Index/withdrawals');?>"><img src="/Public/index/img/personal_btn.png"/></a></li>-->
					<?php if($distribution_config['coin_off'] == 1): ?><li onclick='window.location.href="<?php echo U('Index/add_withdrawal_coin');?>"'>提现金币：<span><?php echo ($redcount); ?>个</span><a href="<?php echo U('Index/add_withdrawal_coin');?>"><img src="/Public/index/img/personal_btn.png"/></a></li><?php endif; ?>
					<li onclick='window.location.href="<?php echo U('Index/extension');?>"'>我的推广<a href="<?php echo U('Index/extension');?>"> <img src="/Public/index/img/personal_btn.png"/></a></li>
					<li onclick='window.location.href="<?php echo U('Redpackets/recharge');?>"'>购买金币<a href="<?php echo U('Redpackets/recharge');?>"> <img src="/Public/index/img/personal_btn.png"/></a></li>
					<li onclick='window.location.href="<?php echo U('Redpackets/redWall');?>"'>我的红包：<span style="color: red;"><?php echo ($redc); ?></span>个<a href="<?php echo U('Redpackets/redWall');?>"><img src="/Public/index/img/personal_btn.png"/></a></li>
					<!--<li onclick='window.location.href="<?php echo U('Activity/index');?>"'>活动管理：<a href="<?php echo U('Activity/index');?>"><img src="/Public/index/img/personal_btn.png"/></a></li>-->
					<li onclick='window.location.href="<?php echo U('Customer/index');?>"'>联系客服：<a href="<?php echo U('Customer/index');?>"><img src="/Public/index/img/personal_btn.png"/></a></li>
				</ul>
			</div>
			<div class="personal_foot">
				<div class="foot_game"><a href="<?php echo U('Index/gamelist');?>">游戏中心</a></div>
				<div class="foot_code"><a href="<?php echo U('Index/extension');?>">个人二维码</a></div>
				<div class="foot_center"><a href="<?php echo U('Index/index');?>">个人中心</a></div>
			</div>
		</div>
	</body>
</html>