<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'/>
	<title>飞机大战</title>
	<meta name='viewport' content='width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no'/>
	<meta name='apple-mobile-web-app-capable' content='yes' />
	<meta name='full-screen' content='true' />
	<meta name='screen-orientation' content='portrait' />
	<meta name='x5-fullscreen' content='true' />
	<meta name='360-fullscreen' content='true' />
	<meta http-equiv='expires' content='0' />
</head>
<body>
    <!-- Protobuf -->
    <!--<script type="text/javascript" src="libs/bytebuffer.js"></script>
    <script type="text/javascript" src="libs/protobuf.js"></script>
	-->
            <script src='/Public/js/jquery-1.8.2.min.js' ></script>
    <script src='/Public/js/layer/layer.js' ></script>
   
	<!--核心包，封装了显示对象渲染，事件，时间管理，时间轴动画，缓动，消息交互,socket，本地存储，鼠标触摸，声音，加载，颜色滤镜，位图字体等-->
	<script type="text/javascript" src="/Public/game/plane/libs/min/laya.core.min.js"></script>
	<!--封装了webgl渲染管线，如果使用webgl渲染，可以在初始化时调用Laya.init(1000,800,laya.webgl.WebGL);-->
    <script type="text/javascript" src="/Public/game/plane/libs/min/laya.webgl.min.js"></script>
	<!--是动画模块，包含了swf动画，骨骼动画等-->
    <script type="text/javascript" src="/Public/game/plane/libs/min/laya.ani.min.js"></script>
	<!--包含更多webgl滤镜，比如外发光，阴影，模糊以及更多-->
    <script type="text/javascript" src="/Public/game/plane/libs/min/laya.filter.min.js"></script>
	<!--封装了html动态排版功能-->
    <script type="text/javascript" src="/Public/game/plane/libs/min/laya.html.min.js"></script>
	<!--粒子类库-->
    <script type="text/javascript" src="/Public/game/plane/libs/min/laya.particle.min.js"></script>
	<!--提供tileMap解析支持-->
    <script type="text/javascript" src="/Public/game/plane/libs/min/laya.tiledmap.min.js"></script>
	<!--提供了制作UI的各种组件实现-->
    <script type="text/javascript" src="/Public/game/plane/libs/min/laya.ui.min.js"></script>
	<!--引入双向通信socket.io-->
	<script type="text/javascript" src="/Public/game/plane/js/socket.io-client/dist/socket.io.js"></script>
	 <!-- 物理引擎matter.js -->
    <!--<script type="text/javascript" src="libs/matter.js"></script>
    <script type="text/javascript" src="libs/matter-RenderLaya.js"></script>-->
	<!--自定义的js(src文件夹下)文件自动添加到下面jsfile模块标签里面里，js的顺序可以手动修改，修改后保留修改的顺序，新增加的js会默认依次追加到标签里-->
	<!--删除标签，ide不会自动添加js文件，请谨慎操作-->
	<!--jsfile--startTag-->
	<script src="/Public/game/plane/js/util.js"></script>
	<script type="text/javascript" src="/Public/game/plane/js/ui/layaUI.max.all.js"></script>
	<script src="/Public/game/plane/js/LayaSample.js"></script>
	<script src="/Public/game/plane/js/GameOverDouble.js"></script>
	<script src="/Public/game/plane/js/GameOverOne.js"></script>
	<script src="/Public/game/plane/js/Score.js"></script>
	<script src="/Public/game/plane/js/Room.js"></script>
	<script src="/Public/game/plane/js/Plane.js"></script>
	<script src="/Public/game/plane/js/Barrier.js"></script>
	<script src="/Public/game/plane/js/WsSocket.js"></script>
	<script src="/Public/game/plane/js/Util.js"></script>
	<script src="/Public/game/plane/js/GameStart.js"></script>
	<script src="/Public/game/plane/js/GameReady.js"></script>
	<script src="/Public/game/plane/js/Game.js"></script>
	<!--jsfile--endTag-->
</body>
</html>