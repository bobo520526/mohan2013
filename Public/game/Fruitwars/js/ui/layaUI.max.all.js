var CLASS$=Laya.class;
var STATICATTR$=Laya.static;
var View=laya.ui.View;
var Dialog=laya.ui.Dialog;
var urlStrings = "/Public/game/Fruitwars/";
var gameOverUI=(function(_super){
		function gameOverUI(){
		    this.btn_reStart=null;
		    this.btn_penson=null;
		    this.total_sco=null;

			gameOverUI.__super.call(this);
		}

		CLASS$(gameOverUI,'ui.gameOverUI',_super);
		var __proto__=gameOverUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("Text",laya.display.Text);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(gameOverUI.uiView);
		}

		STATICATTR$(gameOverUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"width":600,"skin":"comp/shibai_bg.png","height":966},"child":[{"type":"Image","props":{"y":112,"x":25,"skin":urlStrings+"comp/gameover.png"}},{"type":"Image","props":{"y":271,"x":225,"skin":"comp/shibai_ku.png"}},{"type":"Button","props":{"y":648,"x":77,"width":193,"var":"btn_reStart","stateNum":"2","skin":"comp/btn_restart.png","name":"btn_reStart","height":89}},{"type":"Button","props":{"y":648,"x":337,"width":193,"var":"btn_penson","stateNum":"2","skin":"comp/btn_person.png","name":"btn_penson","height":89}}]},{"type":"Text","props":{"y":473,"x":209,"width":254,"var":"total_sco","name":"total_sco","height":61}}]};}
		]);
		return gameOverUI;
	})(View);
var gameStartUI=(function(_super){
		function gameStartUI(){
			
		    this.btn_start=null;

			gameStartUI.__super.call(this);
		}

		CLASS$(gameStartUI,'ui.gameStartUI',_super);
		var __proto__=gameStartUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(gameStartUI.uiView);
		}

		STATICATTR$(gameStartUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"width":600,"skin":urlStrings+"comp/beginGame.png","height":966},"child":[{"type":"Button","props":{"y":633,"x":199,"width":193,"var":"btn_start","stateNum":"2","skin":"comp/btn_start.png","name":"btn_start","height":89}}]}]};}
		]);
		return gameStartUI;
	})(View);
var homeUI=(function(_super){
		function homeUI(){
			
		    this.main_bg2=null;
		    this.item2=null;
		    this.item1=null;
		    this.item0=null;
		    this.btn_right1=null;
		    this.btn_right2=null;
		    this.btn_left1=null;
		    this.btn_left2=null;
		    this.box_1=null;
		    this.fruit_1=null;
		    this.item0_red=null;
		    this.item1_red=null;
		    this.item2_red=null;

			homeUI.__super.call(this);
		}

		CLASS$(homeUI,'ui.homeUI',_super);
		var __proto__=homeUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(homeUI.uiView);
		}

		STATICATTR$(homeUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Sprite","props":{"y":0,"x":0,"var":"main_bg2","name":"main_bg2"},"child":[{"type":"Box","props":{"y":666,"x":-16,"visible":false,"var":"item2","name":"item2"},"child":[{"type":"Image","props":{"y":1,"x":4,"width":315,"skin":"comp/left_3.png","height":160}},{"type":"Image","props":{"y":0,"x":314,"width":314,"skin":"comp/right_3.png","height":160}}]},{"type":"Box","props":{"y":636,"x":-15,"visible":false,"var":"item1","name":"item1"},"child":[{"type":"Image","props":{"y":1,"x":4,"width":315,"skin":"comp/left_2.png","height":160}},{"type":"Image","props":{"y":0,"x":314,"width":314,"skin":"comp/right_2.png","height":160}}]},{"type":"Box","props":{"y":628,"x":-17,"width":624,"var":"item0","name":"item0","height":161},"child":[{"type":"Image","props":{"y":0,"x":4,"width":315,"skin":"comp/left_1.png","height":160}},{"type":"Image","props":{"y":0,"x":314,"width":314,"skin":"comp/right_1.png","height":160}}]},{"type":"Image","props":{"y":660,"x":161,"skin":"comp/dangongjia.png"}},{"type":"Image","props":{"y":841,"x":438,"var":"btn_right1","skin":"comp/fruit_2.png","name":"btn_right1"}},{"type":"Image","props":{"y":840,"x":438,"visible":false,"var":"btn_right2","skin":"comp/shiliu_hui.png","name":"btn_right2"}},{"type":"Image","props":{"y":841,"x":52,"var":"btn_left1","skin":"comp/fruit_3.png","name":"btn_left1"}},{"type":"Image","props":{"y":834,"x":52,"visible":false,"var":"btn_left2","skin":"comp/xigua_hui.png","name":"btn_left2"}},{"type":"Box","props":{"y":814,"x":301,"width":120,"var":"box_1","pivotY":54.54545454545439,"pivotX":61.81818181818184,"name":"box_1","height":100},"child":[{"type":"Image","props":{"y":0,"x":7,"var":"fruit_1","skin":"comp/fruit_1.png","name":"fruit_1"}},{"type":"Image","props":{"y":52,"x":25,"skin":"comp/danbao.png"}}]},{"type":"Box","props":{"y":628,"x":-16,"width":624,"visible":false,"var":"item0_red","name":"item0_red","height":161},"child":[{"type":"Image","props":{"y":0,"x":4,"width":315,"skin":"comp/left_1_red.png","height":160}},{"type":"Image","props":{"y":0,"x":314,"width":314,"skin":"comp/right_1_red.png","height":160}}]},{"type":"Box","props":{"y":635,"x":-14,"visible":false,"var":"item1_red","name":"item1_red"},"child":[{"type":"Image","props":{"y":1,"x":4,"width":315,"skin":"comp/left_2_red.png","height":160}},{"type":"Image","props":{"y":0,"x":314,"width":314,"skin":"comp/right_2_red.png","height":160}}]},{"type":"Box","props":{"y":667,"x":-15,"visible":false,"var":"item2_red","name":"item2_red"},"child":[{"type":"Image","props":{"y":1,"x":4,"width":315,"skin":"comp/left_3_red.png","height":160}},{"type":"Image","props":{"y":0,"x":314,"width":314,"skin":"comp/right_3_red.png","height":160}}]}]}]};}
		]);
		return homeUI;
	})(View);
var homeBgUI=(function(_super){
		function homeBgUI(){
			

			homeBgUI.__super.call(this);
		}

		CLASS$(homeBgUI,'ui.homeBgUI',_super);
		var __proto__=homeBgUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(homeBgUI.uiView);
		}

		STATICATTR$(homeBgUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"width":600,"skin":"comp/game_bg.png","height":966}}]};}
		]);
		return homeBgUI;
	})(View);
var homeTopbgUI=(function(_super){
		function homeTopbgUI(){
			
		    this.Hp_bar=null;
		    this.enemy_load=null;
		    this.min=null;
		    this.tenSec=null;
		    this.sec=null;
		    this.ttf=null;

			homeTopbgUI.__super.call(this);
		}

		CLASS$(homeTopbgUI,'ui.homeTopbgUI',_super);
		var __proto__=homeTopbgUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("Text",laya.display.Text);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(homeTopbgUI.uiView);
		}

		STATICATTR$(homeTopbgUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"width":600,"skin":urlStrings+"comp/shangfangui.png"}},{"type":"Image","props":{"y":31,"x":394,"skin":"comp/jinbi.png"}},{"type":"Image","props":{"y":48,"x":15,"width":22,"var":"Hp_bar","skin":"comp/HPBar.png","sizeGrid":"10,15,10,15","name":"Hp_bar","height":32}},{"type":"Image","props":{"y":26,"x":-8,"var":"enemy_load","skin":"comp/yidongwa.png","name":"enemy_load"}},{"type":"Box","props":{"y":54,"x":241},"child":[{"type":"Clip","props":{"y":0,"x":0,"width":29,"skin":"comp/clip_number_time.png","index":0,"height":35,"clipX":10}},{"type":"Clip","props":{"x":27,"var":"min","skin":"comp/clip_number_time.png","name":"min","clipX":10}},{"type":"Clip","props":{"x":70,"var":"tenSec","skin":"comp/clip_number_time.png","name":"tenSec","clipX":10}},{"type":"Clip","props":{"x":95,"var":"sec","skin":"comp/clip_number_time.png","name":"sec","clipX":10}}]},{"type":"Image","props":{"y":5,"x":281,"width":38,"skin":"comp/clock.png","height":35}},{"type":"Text","props":{"y":38,"x":454,"width":142,"var":"ttf","name":"ttf","height":50,"color":"#c5ac2b","bold":true,"align":"center"}}]};}
		]);
		return homeTopbgUI;
	})(View);