<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'/>
	<title>捕鱼达人</title>
	<meta name='viewport' content='width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no'/>
	<meta name='apple-mobile-web-app-capable' content='yes' />
	<meta name='full-screen' content='true' />
	<meta name='screen-orientation' content='portrait' />
	<meta name='x5-fullscreen' content='true' />
	<meta name='360-fullscreen' content='true' />
	<meta http-equiv='expires' content='0' />
		<script src="/Public/js/jquery-1.8.2.min.js" type="text/javascript"></script>
			<script src="/Public/js/layer/layer.js" type="text/javascript"></script>
</head>
<body>
	<!--时间-->
	<input type="hidden" value="<?php echo ($time_config); ?>" id="time_config"/>
	<!--游戏状态-->
	<input type="hidden" value="<?php echo ($game_status); ?>" id="game_status"/>
	<!--游戏关闭信息-->
	<input type="hidden" value="<?php echo ($msg); ?>" id="msg"/>
	<input type="hidden" value="<?php echo U('Redpackets/recharge');?>" id="rg"/>
	<input type="hidden" value="<?php echo ($mem_coin); ?>" id="mem_coin"/>
	<input type="hidden" value="<?php echo ($game_coin); ?>" id="game_coin"/>
	<input type="hidden" value="<?php echo ($gl); ?>" id="gl"/>
        <input type="hidden" value="<?php echo U('Index/index');?>" id="grzx"/>
    <!-- Protobuf -->
    <!--<script type="text/javascript" src="libs/bytebuffer.js"></script>
    <script type="text/javascript" src="libs/protobuf.js"></script>
	-->

	<script>
		// var gl = document.getElementById('gl').value;
		// console.log(gl);
	</script>
	<!--核心包，封装了显示对象渲染，事件，时间管理，时间轴动画，缓动，消息交互,socket，本地存储，鼠标触摸，声音，加载，颜色滤镜，位图字体等-->
	<script type="text/javascript" src="/Public/game/catchfish/libs/min/laya.core.min.js"></script>
	<!--封装了webgl渲染管线，如果使用webgl渲染，可以在初始化时调用Laya.init(1000,800,laya.webgl.WebGL);-->
    <script type="text/javascript" src="/Public/game/catchfish/libs/min/laya.webgl.min.js"></script>
	<!--是动画模块，包含了swf动画，骨骼动画等-->
    <script type="text/javascript" src="/Public/game/catchfish/libs/min/laya.ani.min.js"></script>
	<!--包含更多webgl滤镜，比如外发光，阴影，模糊以及更多-->
    <script type="text/javascript" src="/Public/game/catchfish/libs/min/laya.filter.min.js"></script>
	<!--封装了html动态排版功能-->
    <script type="text/javascript" src="/Public/game/catchfish/libs/min/laya.html.min.js"></script>
	<!--粒子类库-->
    <script type="text/javascript" src="/Public/game/catchfish/libs/min/laya.particle.min.js"></script>
	<!--提供tileMap解析支持-->
    <script type="text/javascript" src="/Public/game/catchfish/libs/min/laya.tiledmap.min.js"></script>
	<!--提供了制作UI的各种组件实现-->
    <script type="text/javascript" src="/Public/game/catchfish/libs/min/laya.ui.min.js"></script>

	 <!-- 物理引擎matter.js -->
    <!--<script type="text/javascript" src="libs/matter.js"></script>
    <script type="text/javascript" src="libs/matter-RenderLaya.js"></script>-->
	<!--自定义的js(src文件夹下)文件自动添加到下面jsfile模块标签里面里，js的顺序可以手动修改，修改后保留修改的顺序，新增加的js会默认依次追加到标签里-->
	<!--删除标签，ide不会自动添加js文件，请谨慎操作-->

	<script src="/Public/game/catchfish/js/ui/layaUI.max.all.js"></script>
	<!--jsfile--startTag-->
	<script src="/Public/game/catchfish/js/data.js"></script>
	<script src="/Public/game/catchfish/js/hitTest.js"></script>
	<script src="/Public/game/catchfish/js/bullet.js"></script>
	<script src="/Public/game/catchfish/js/StartMeun.js"></script>
	<script src="/Public/game/catchfish/js/main.js"></script>
	<script src="/Public/game/catchfish/js/LayaSample.js"></script>
	<!--jsfile--endTag-->
</body>
</html>