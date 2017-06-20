<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>不要跟我说你不喜欢点红包？</title>
	<script src='/Public/js/jquery-1.8.2.min.js' ></script>
	<!-- <script src="/Public/js/jquery-1.8.2.min.js" type="text/javascript"></script> -->
	<link rel="stylesheet" type="text/css" href="/Public/redpack/css/myrest.css"/>
	<link rel="stylesheet" type="text/css" href="/Public/redpack/css/index.css"/>
	<script src="/Public/redpack/js/rem.js" type="text/javascript" charset="utf-8"></script>
	<script id="tims" src="/Public/redpack/js/hongbao.js" type="text/javascript" charset="utf-8"></script>
	<script src="/Public/js/layer/layer.js" type="text/javascript"></script>
</head>
<body>
	<audio src="/Public/redpack/8607.mp3" autoplay="autoplay" loop="loop" id="Jaudio"></audio>
	<script>
		function audioAutoPlay(id){
			var audio = document.getElementById(id),
					play = function(){
						audio.play();
						document.removeEventListener("touchstart",play, false);
					};
			audio.play();
			document.addEventListener("WeixinJSBridgeReady", function () {
				play();
			}, false);
			document.addEventListener('YixinJSBridgeReady', function() {
				play();
			}, false);
			document.addEventListener("touchstart",play, false);
		}
		audioAutoPlay('Jaudio');
	</script>
<!--进入游戏首页-->
<div id="first" >
		<div class="box1">
			<img src="/Public/redpack/images/index_btm.png" id="firstbtn" />
		</div>
	<input type="hidden" id="member_coin" value="<?php echo ($member_coin); ?>" />
	<input type="hidden" id="coin_config" value="<?php echo ($coin_config); ?>" />
	<input type="hidden" id="games_state" value="<?php echo ($games_state); ?>" />
	<input type="hidden" id="games_time" value="<?php echo ($games_time); ?>" />
	</div>
<!--游戏主体点红包-->
<div id="second"  class="dn">
	<div id="daojishi"><ul>
	    <li><?php echo ($decade); ?></li>
	    <li><?php echo ($unit); ?></li>
	    <li>'</li>
	    <li>0</li>
	    <li>0</li>
	    <li>''</li>
	</ul></div>
	<!--<h1 id="score" style="text-align: center  " class="dn">0</h1>-->
	<div id="main">
		<div id="container"></div>
		</div>
	</div>
<!--游戏结束页面获得红包统计-->
	<div id="third" class="dn">
		<div id="bg">
		<div class="box3">
			<!--一键开启页面，点击跳转到金币页面-->
			<div class="open_bg" id="div1">
				<span id="score" >0</span>
				<img src="/Public/redpack/images/open_bg.png"/>
			</div>

			<div class="open_btm">
				<!--点击开启金币页面-->
				<img src="/Public/redpack/images/open_btm.png" id="openbtn"/>
			</div>
		</div>
	</div>
	</div>

<!--拆红包获得的金币数以及重玩一次-->
	<!--<div id="four" class="dn">-->
	    <!--<div id="bg4">-->
		<!--<div class="box4">-->

			<!--<div class="agin_bg">-->
				<!--<input type="text" name="partNumber" id="txt" value="" readonly/>-->
			<!--</div>-->
			<!--<div class="agin_goon"><img src="/Public/redpack/images/goon.png" id="agin_goon"/></div>-->
			<!--<div class="agin_btm"><img src="/Public/redpack/images/agin_btm.png" id="againbtn"/></div>-->
			<!--<div class="back_index">-->
				<!--<a href="<?php echo U('Index/index');?>"><img src="/Public/redpack/images/person.png" id="backbtn"/></a>-->
			<!--</div>-->
		<!--</div>-->
	    <!--</div>-->
		<!--</div>-->

<input type="hidden" id="durl" value="<?php echo U('Redpackets/member_distributions');?>" />
<!--红包墙-->
	<div id="five"  class="dn" >
		<!--透明层-->
		<div id="bg5">
			<img src="/Public/redpack/images/openAll.png" id="openAll" class="open_All"/>
			<span id="hbnum"></span>
			<div class="hongbaoceng">
				<!--红包显示区域-->
				<div id="box5">
				</div></div>
		</div>
	</div>
<!--红包结束页面-->
<div id="six" class="dn">
	<div id="bg6">
		<div class="box6">
			<div class="endpic">
				<img src="/Public/redpack/images/end.png" alt="">
			</div>
			<div class="endagain"><img src="/Public/redpack/images/agin_btm.png" id="agin_goon2"/></div>
			<div class="endperson"><a href="<?php echo U('Index/index');?>"><img src="/Public/redpack/images/person.png" id="againbtn2"/><a></div>
		</div>
	</div>
</div>

	<script>
		$(function(){
			//一键点击红包
			$("#openAll").click(function(){
				$('.t1').css('display','block');
				$('.redcell').addClass('red_nums');  //表示已经开过红包了
				$("#hbnum").text("还剩0个红包");
				// 雷振兴注释开始
// 				layer.load(1);
// 				//获取所以列表
// 				var addHtml;
// 				var getAllBox = $("#box5 .redcell").length;
// 				var allArray = [];
// 				var i = 0;
// 				reRun();
// 				function reRun(){
// 					$.post("<?php echo U('Redpackets/operate');?>",{"s":1,'open_red_pack':getAllBox},function (data){
// 						allArray = data.price;
// 						showData();
// 					},"json");
// 				}
// 				function showData(){
// 					for(var i=0;i<allArray.length;i++){
// 						if(!$("#box5 .redcell").eq(i).hasClass("yellcell")){
// 							//一键开启红包
// 							$("#box5 .redcell").addClass("yellcell");
// //							$("#box5 .redcell").removeClass("redcell");
// 							$("#hbnum").text("还剩0个红包");
// 						}
// 						$("#box5 .redcell").eq(i).html("<div class='t1'>"+allArray[i]+"</div>")
// 						if(i>=getAllBox-1){
// 							setTimeout(function(){
// 								$("#six,#bg6").show();
// 							},getAllBox*350)
// 						}
// 					}
// 					layer.closeAll();
// 				}
				// 雷振兴注释结束
			})
			//单独点击红包
			$("#box5").on("click",".redcell",function(){
				if($(this).children().css('display','block')){
					$(this).addClass('red_nums');  //表示已经开过红包了
					var now_red_count = allArray.length-$('.red_nums').length;
					$("#hbnum").text("还剩"+now_red_count+"个红包");;
				}
		  	});
		})
		function get_member_all_coin(){

		}
	</script>
</body>
</html>
<script>
	$(document).ready(function(){
		var js_src = $("#tims").attr("src");
		var timsp = new Date().getTime();
		$("#tims").attr("src",js_src + timsp);
	});
	function distributions(){
			var url = $("#durl").val();
			$.post(url,{'distrib':'member_distributions'},function (data){

			});
	}
	function cut_member_coin(){
		var url = "<?php echo U('Redpackets/member_cut_coin');?>";
		$.post(url,{'parm':'parm'},function(data){
			if(data.state == 1){
				$("#games_state").val(1);
				$("#member_coin").val(data.coin);
			}else{
				layer.msg(data.msg);
			}
		},"json");
	}
</script>