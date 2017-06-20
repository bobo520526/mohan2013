var CLASS$=Laya.class;
var STATICATTR$=Laya.static;
var View=laya.ui.View;
var Dialog=laya.ui.Dialog;
var urlString = '/Public/game/pumpkin/';
var gameOverlUI=(function(_super){
		function gameOverlUI(){
			
		    this.btn_again=null;
		    this.btn_person=null;
		    this.score=null;
		    this.s1=null;
		    this.s2=null;
		    this.s3=null;
		    this.s4=null;

			gameOverlUI.__super.call(this);
		}

		CLASS$(gameOverlUI,'ui.gameOverlUI',_super);
		var __proto__=gameOverlUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(gameOverlUI.uiView);
		}

		STATICATTR$(gameOverlUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":139,"x":53,"width":503,"skin":"comp/JIESHU.png","height":106}},{"type":"Image","props":{"y":276,"x":104,"width":389,"skin":"comp/Jjifenban.png","height":586}},{"type":"Image","props":{"y":673,"x":234,"width":145,"var":"btn_again","skin":"comp/zaiwanyici.png","name":"btn_again","height":38}},{"type":"Image","props":{"y":590,"x":234,"width":145,"var":"btn_person","skin":"comp/jieshuyouxi.png","height":38}},{"type":"Box","props":{"y":461,"x":192,"var":"score","name":"score"},"child":[{"type":"Clip","props":{"y":2,"x":0,"var":"s1","skin":"comp/clip_number_score.png","name":"s1","clipX":10}},{"type":"Clip","props":{"y":2,"x":38,"var":"s2","skin":"comp/clip_number_score.png","name":"s2","clipX":10}},{"type":"Clip","props":{"y":2,"x":77,"var":"s3","skin":"comp/clip_number_score.png","name":"s3","clipX":10}},{"type":"Clip","props":{"y":2,"x":115,"var":"s4","skin":"comp/clip_number_score.png","name":"s4","clipX":10}}]}]};}
		]);
		return gameOverlUI;
	})(Dialog);
var GamestartUI=(function(_super){
		function GamestartUI(){
			
		    this.ani1=null;
		    this.btn_start=null;
		    this.deng=null;

			GamestartUI.__super.call(this);
		}

		CLASS$(GamestartUI,'ui.GamestartUI',_super);
		var __proto__=GamestartUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(GamestartUI.uiView);
		}

		STATICATTR$(GamestartUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"width":600,"skin":urlString+"comp/start_bg.png","height":966}},{"type":"Button","props":{"y":694,"x":274,"width":200,"var":"btn_start","stateNum":"1","skin":"comp/btn_start.png","rotation":0,"pivotY":35,"pivotX":100,"name":"btn_start","height":70},"compId":3},{"type":"Image","props":{"y":111,"x":40,"width":527,"var":"deng","skin":"comp/deng.png","name":"deng","height":267},"compId":4}],"animations":[{"nodes":[{"target":4,"keyframes":{"y":[{"value":-264,"tweenMethod":"linearNone","tween":true,"target":4,"key":"y","index":0},{"value":109,"tweenMethod":"linearNone","tween":true,"target":4,"key":"y","index":27},{"value":49,"tweenMethod":"linearNone","tween":true,"target":4,"key":"y","index":36},{"value":111,"tweenMethod":"linearNone","tween":true,"target":4,"key":"y","index":41}],"x":[{"value":40,"tweenMethod":"linearNone","tween":true,"target":4,"key":"x","index":0}],"width":[{"value":527,"tweenMethod":"linearNone","tween":true,"target":4,"key":"width","index":0}],"height":[{"value":267,"tweenMethod":"linearNone","tween":true,"target":4,"key":"height","index":0}]}},{"target":3,"keyframes":{"y":[{"value":694,"tweenMethod":"linearNone","tween":true,"target":3,"key":"y","index":0},{"value":704,"tweenMethod":"linearNone","tween":true,"target":3,"key":"y","index":17},{"value":694,"tweenMethod":"linearNone","tween":true,"target":3,"key":"y","index":35}],"x":[{"value":274,"tweenMethod":"linearNone","tween":true,"target":3,"key":"x","index":0},{"value":289,"tweenMethod":"linearNone","tween":true,"target":3,"key":"x","index":17},{"value":274,"tweenMethod":"linearNone","tween":true,"target":3,"key":"x","index":35}],"width":[{"value":200,"tweenMethod":"linearNone","tween":true,"target":3,"key":"width","index":0},{"value":230,"tweenMethod":"linearNone","tween":true,"target":3,"key":"width","index":17},{"value":200,"tweenMethod":"linearNone","tween":true,"target":3,"key":"width","index":35}],"rotation":[{"value":0,"tweenMethod":"linearNone","tween":true,"target":3,"key":"rotation","index":0},{"value":-10,"tweenMethod":"linearNone","tween":true,"target":3,"key":"rotation","index":17},{"value":10,"tweenMethod":"linearNone","tween":true,"target":3,"key":"rotation","index":27},{"value":0,"tweenMethod":"linearNone","tween":true,"target":3,"key":"rotation","index":35}],"pivotY":[{"value":35,"tweenMethod":"linearNone","tween":true,"target":3,"key":"pivotY","index":0},{"value":45,"tweenMethod":"linearNone","tween":true,"target":3,"key":"pivotY","index":17},{"value":35,"tweenMethod":"linearNone","tween":true,"target":3,"key":"pivotY","index":35}],"pivotX":[{"value":100,"tweenMethod":"linearNone","tween":true,"target":3,"key":"pivotX","index":0},{"value":115,"tweenMethod":"linearNone","tween":true,"target":3,"key":"pivotX","index":17},{"value":100,"tweenMethod":"linearNone","tween":true,"target":3,"key":"pivotX","index":35}],"height":[{"value":70,"tweenMethod":"linearNone","tween":true,"target":3,"key":"height","index":0},{"value":90,"tweenMethod":"linearNone","tween":true,"target":3,"key":"height","index":17},{"value":70,"tweenMethod":"linearNone","tween":true,"target":3,"key":"height","index":35}]}}],"name":"ani1","id":1,"frameRate":60,"action":1}]};}
		]);
		return GamestartUI;
	})(View);
var homeUI=(function(_super){
		function homeUI(){
			
		    this.musiczhuan=null;
		    this.musicplay=null;
		    this.musicstop=null;
		    this.score=null;
		    this.time_item1=null;
		    this.time_item2=null;
		    this.time_item3=null;
		    this.time_item4=null;
		    this.wxTx=null;

			homeUI.__super.call(this);
		}

		CLASS$(homeUI,'ui.homeUI',_super);
		var __proto__=homeUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("Text",laya.display.Text);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(homeUI.uiView);
		}

		STATICATTR$(homeUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"width":600,"skin":urlString+"comp/home_bg.png","height":966}},{"type":"Image","props":{"y":729,"x":0,"width":600,"skin":urlString+"comp/boy.png"}},{"type":"Image","props":{"y":30,"x":505,"width":72,"var":"musicplay","skin":"comp/musicOn.png","rotation":0,"pivotY":0,"pivotX":0,"name":"musicplay","height":72},"compId":4},{"type":"Image","props":{"y":31,"x":506,"width":70,"visible":false,"var":"musicstop","skin":"comp/musicOff.png","height":70}},{"type":"Image","props":{"y":6,"x":268,"width":64,"skin":"comp/clock.png","height":64}},{"type":"Image","props":{"y":110,"x":301,"skin":"comp/point.png"}},{"type":"Text","props":{"y":47,"x":151,"width":97,"var":"score","text":" ","name":"score","height":45,"fontSize":36,"font":"Microsoft YaHei","color":"#ff5b48"}},{"type":"Box","props":{"y":82,"x":226},"child":[{"type":"Clip","props":{"y":0,"x":0,"var":"time_item1","skin":"comp/clip_number_time.png","name":"time_item1","index":3,"clipX":10}},{"type":"Clip","props":{"y":1,"x":37,"var":"time_item2","skin":"comp/clip_number_time.png","name":"time_item2","clipX":10}},{"type":"Clip","props":{"y":1,"x":88,"var":"time_item3","skin":"comp/clip_number_time.png","name":"time_item3","clipX":10}},{"type":"Clip","props":{"y":1,"x":125,"width":33,"var":"time_item4","skin":"comp/clip_number_time.png","name":"time_item4","height":42,"clipX":10}}]},{"type":"Image","props":{"y":7,"x":13,"width":142,"var":"wxTx","skin":"comp/touxiang.png","name":"wxTx","height":147},"child":[{"type":"Image","props":{"y":5,"x":2,"skin":urlString+"comp/rodai.png","renderType":"mask","name":"wxTx"}}]}],"animations":[{"nodes":[{"target":4,"keyframes":{"y":[{"value":69,"tweenMethod":"linearNone","tween":true,"target":4,"key":"y","index":0}],"x":[{"value":540,"tweenMethod":"linearNone","tween":true,"target":4,"key":"x","index":0}],"width":[{"value":70,"tweenMethod":"linearNone","tween":true,"target":4,"key":"width","index":0}],"var":[{"value":null,"tweenMethod":"linearNone","tween":false,"target":4,"key":"var","index":0},{"value":"musicPlay","tweenMethod":"linearNone","tween":false,"target":4,"key":"var","index":40}],"rotation":[{"value":0,"tweenMethod":"linearNone","tween":true,"target":4,"key":"rotation","index":0},{"value":90,"tweenMethod":"linearNone","tween":true,"target":4,"key":"rotation","index":10},{"value":180,"tweenMethod":"linearNone","tween":true,"target":4,"key":"rotation","index":20},{"value":270,"tweenMethod":"linearNone","tween":true,"target":4,"key":"rotation","index":30},{"value":360,"tweenMethod":"linearNone","tween":true,"target":4,"key":"rotation","index":40}],"pivotY":[{"value":35,"tweenMethod":"linearNone","tween":true,"target":4,"key":"pivotY","index":0}],"pivotX":[{"value":35,"tweenMethod":"linearNone","tween":true,"target":4,"key":"pivotX","index":0}],"name":[{"value":"","tweenMethod":"linearNone","tween":false,"target":4,"key":"name","index":0},{"value":"musicPlay","tweenMethod":"linearNone","tween":false,"target":4,"key":"name","index":40}],"height":[{"value":70,"tweenMethod":"linearNone","tween":true,"target":4,"key":"height","index":0}]}}],"name":"musiczhuan","id":1,"frameRate":24,"action":2}]};}
		]);
		return homeUI;
	})(View);