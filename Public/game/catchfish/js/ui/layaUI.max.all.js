var CLASS$=Laya.class;
var STATICATTR$=Laya.static;
var View=laya.ui.View;
var Dialog=laya.ui.Dialog;
var gameOver_DlogUI=(function(_super){
		function gameOver_DlogUI(){

		    this.but_overBgColse=null;
		    this.but_back=null;
		    this.but_game=null;

			gameOver_DlogUI.__super.call(this);
		}

		CLASS$(gameOver_DlogUI,'ui.gameOver_DlogUI',_super);
		var __proto__=gameOver_DlogUI.prototype;
		__proto__.createChildren=function(){

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(gameOver_DlogUI.uiView);
		}

		STATICATTR$(gameOver_DlogUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":145,"x":121,"skin":"comp/overDlog.png"}},{"type":"Image","props":{"y":161,"x":475,"var":"but_overBgColse","skin":"comp/overDlog_close.png","name":"but_overBgColse"}},{"type":"Button","props":{"y":769,"x":181,"var":"but_back","stateNum":"1","skin":"comp/button_back.png","name":"but_back"}},{"type":"Button","props":{"y":636,"x":181,"var":"but_game","stateNum":"1","skin":"comp/button_game.png","name":"but_game"}}]};}
		]);
		return gameOver_DlogUI;
	})(Dialog);
var homeUI=(function(_super){
		function homeUI(){

		    this.cannon_0=null;
		    this.cannon_1=null;
		    this.cannon_2=null;
		    this.cannon_3=null;
		    this.cannon_4=null;
		    this.cannon_5=null;
		    this.cannon_6=null;
		    this.btn_add=null;
		    this.btn_jj=null;
		    this.goldMun=null;
		    this.overTime=null;

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
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":986},"child":[{"type":"Image","props":{"y":0,"x":0,"width":600,"skin":"comp/game_bg_2_hd.jpg","height":966}},{"type":"Image","props":{"y":887,"x":-5,"width":612,"skin":"/Public/game/catchfish/comp/bottom_bg.png","height":95}},{"type":"Image","props":{"y":947,"x":346,"width":128,"var":"cannon_0","skin":"comp/cannon1.png","pivotY":98.87403237156923,"pivotX":69.91555242786762,"name":"cannon_0","height":157}},{"type":"Image","props":{"y":950,"x":342,"width":136,"visible":false,"var":"cannon_1","skin":"comp/cannon2.png","pivotY":103.09641097818428,"pivotX":69.73961998592534,"name":"cannon_1","height":166}},{"type":"Image","props":{"y":948,"x":346,"width":136,"visible":false,"var":"cannon_2","skin":"comp/cannon3.png","pivotY":101.68895144264593,"pivotX":71.32301196340597,"name":"cannon_2","height":165}},{"type":"Image","props":{"y":953,"x":342,"width":130,"visible":false,"var":"cannon_3","skin":"comp/cannon4.png","pivotY":105.45390570021107,"pivotX":63.793103448275815,"name":"cannon_3","height":158}},{"type":"Image","props":{"y":947,"x":341,"width":132,"visible":false,"var":"cannon_4","skin":"comp/cannon5.png","pivotY":97.60731878958472,"pivotX":65.20056298381422,"name":"cannon_4","height":161}},{"type":"Image","props":{"y":948,"x":343,"width":124,"visible":false,"var":"cannon_5","skin":"comp/cannon6.png","pivotY":90.07741027445445,"pivotX":61.752287121745155,"name":"cannon_5","height":145}},{"type":"Image","props":{"y":943,"x":344,"width":125,"visible":false,"var":"cannon_6","skin":"comp/cannon7.png","pivotY":94.2997888810695,"pivotX":62.06896551724134,"name":"cannon_6","height":153}},{"type":"Button","props":{"y":890.0000000000001,"x":350,"var":"btn_add","stateNum":"1","skin":"comp/bottom_add.png","name":"btn_add"}},{"type":"Image","props":{"y":891,"x":242.00000000000003,"width":90,"var":"btn_jj","skin":"comp/bottom_rm.png","name":"btn_jj","height":110}},{"type":"Text","props":{"y":13,"x":478,"width":89,"var":"goldMun","valign":"middle","text":"2000","strokeColor":"#e7de7e","stroke":1,"name":"goldMunt","height":41,"fontSize":"30","color":"#deb225","align":"center"}},{"type":"Label","props":{"y":14,"x":47,"width":108,"var":"overTime","valign":"middle","text":"00 : 00","name":"overTime","height":32,"fontSize":32,"color":"#e2af2b","align":"center"}},{"type":"Image","props":{"y":13,"x":8,"width":40,"skin":"comp/Time.png","height":40}},{"type":"Image","props":{"y":5,"x":429,"width":49,"skin":"comp/gold.png","height":49}}]};}
		]);
		return homeUI;
	})(View);
var stratMeunUI=(function(_super){
		function stratMeunUI(){

		    this.but_startGame=null;

			stratMeunUI.__super.call(this);
		}

		CLASS$(stratMeunUI,'ui.stratMeunUI',_super);
		var __proto__=stratMeunUI.prototype;
		__proto__.createChildren=function(){

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(stratMeunUI.uiView);
		}

		STATICATTR$(stratMeunUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"/Public/game/catchfish/comp/stratBg.png"}},{"type":"Button","props":{"y":619,"x":205,"var":"but_startGame","stateNum":"1","skin":"comp/button.png","name":"but_startGame"}}]};}
		]);
		return stratMeunUI;
	})(View);
