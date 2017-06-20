<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html style="font-size: 10px">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
		<title>游戏中心</title>
		<!-- <link rel="stylesheet" href="/Public/index/css/myrest.css" />
		<link rel="stylesheet" href="/Public/index/css/GameCentre.css" /> -->
		<link rel="stylesheet" href="/Public/index/css/myrest.css" />
		<link rel="stylesheet" href="/Public/index/css/GameCentre.css" />
		<!-- 雷振兴 -->
		<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		<script>
		wx.config({
		   debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
		   appId: "<?php echo ($getSignPackage['appId']); ?>", // 必填，公众号的唯一标识
		   timestamp:"<?php echo ($getSignPackage['timestamp']); ?>" , // 必填，生成签名的时间戳
		   nonceStr: "<?php echo ($getSignPackage['nonceStr']); ?>", // 必填，生成签名的随机串
		   signature: "<?php echo ($getSignPackage['signature']); ?>",// 必填，签名，见附录1
		   jsApiList: ['onMenuShareAppMessage','onMenuShareTimeline'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
		 });
		 
		 wx.ready(function(){
		  //分享给朋友
		  wx.onMenuShareAppMessage({
		    title: '标题', // 分享标题 此处$title可在控制器端传递也可在页面传递 页面传递讲解在下面哦
		    desc: '描述', //分享描述
		    link: 'url', // 分享链接
		    imgUrl: 'http://mohan2013/index.php/Mobile/Index/gamelist', // 分享图标
		    type: '', // 分享类型,music、video或link，不填默认为link
		    dataUrl: 'http://mohan2013/Uploads/20170607/59375aebd4a58.png', // 如果type是music或video，则要提供数据链接，默认为空
		    success: function () {
		       alert('分享成功');
		    },
		    cancel: function () {
		      // 用户取消分享后执行的回调函数
		      // alert('取消分享');
		    }
		  });
		  //分享到朋友圈
		   wx.onMenuShareTimeline({
		    title: '<?php echo ($title); ?>', // 分享标题
		    desc: '<?php echo ($desc); ?>', // 分享描述
		    link: '<?php echo ($link); ?>', // 分享链接
		    imgUrl: '<?php echo ($imgurl); ?>', // 分享图标
		    success: function () {
		      // 用户确认分享后执行的回调函数
		    },
		    cancel: function () {
		      // 用户取消分享后执行的回调函数
		    }
		  });
		 });
		 
		</script>
		<!-- 雷振兴结束 -->
	</head>
	<body>
		<!-- <img src="/Public/index/img/banner.png" alt="" /> -->
		<img src="/Public/index/img/banner.png" alt=""  class="banner" />
		<h1>游戏列表</h1>
		<div class="game_box clearFloat">
			<ul class="clearFloat">
			<?php if(is_array($ary)): $i = 0; $__LIST__ = $ary;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><!-- 游戏项模版 -->
				<!-- 雷振兴 -->
					<li>
						<a href="/Mobile/<?php echo ($vo["url"]); ?>"><img src="/<?php echo ($vo["game_logo"]); ?>"><p><?php echo ($vo["gname"]); ?></p><input style="background: url('/Public/index/img/btn_start.png') no-repeat;
	-webkit-background-size:100%;
	background-size:100%;" type="button"></a>
					</li>
				<!-- 雷振兴结束 --><?php endforeach; endif; else: echo "" ;endif; ?>
					<li style="width: 100%;
	text-align: center;
	font-size: 1.2rem;
	padding-bottom: 1rem;">
						<p style="font-weight: 600;">	M O R E </p>
						<span>更多 游戏<br>敬请 期待</span>
					</li>
                                        
			</ul>
		</div>
	</body>
</html>