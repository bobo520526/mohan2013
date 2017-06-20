var CLASS$=Laya.class;
var STATICATTR$=Laya.static;
var View=laya.ui.View;
var Dialog=laya.ui.Dialog;
var GameUI=(function(_super){
		function GameUI(){

		    this.cock_area=null;
		    this.time=null;
		    this.time_ten=null;
		    this.time_unit=null;
		    this.score=null;
		    this.score_1=null;
		    this.score_2=null;
		    this.score_3=null;
		    this.combo=null;
		    this.combo_1=null;
		    this.combo_2=null;

			GameUI.__super.call(this);
		}

		CLASS$(GameUI,'ui.GameUI',_super);
		var __proto__=GameUI.prototype;
		__proto__.createChildren=function(){

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(GameUI.uiView);
		}

		STATICATTR$(GameUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"/Public/game/basketball/bin/comp/ui/img_bg.png"}},{"type":"Image","props":{"y":847,"x":-3,"width":603,"skin":"/Public/game/basketball/bin/comp/ui/ball_bg.png","height":119}},{"type":"Image","props":{"y":831,"x":0,"width":598,"var":"cock_area","skin":"/Public/game/basketball/bin/comp/ui/ball_out.png","name":"cock_area","height":153}},{"type":"Box","props":{"y":31,"x":122,"var":"time","name":"time"},"child":[{"type":"Clip","props":{"width":43,"var":"time_ten","skin":"comp/ui/clip_fnt_time.png","name":"time_ten","index":3,"height":50,"clipX":10,"clipWidth":48.65}},{"type":"Clip","props":{"x":43,"width":43,"var":"time_unit","skin":"comp/ui/clip_fnt_time.png","name":"time_unit","height":50,"clipX":10,"clipWidth":48.65}}]},{"type":"Box","props":{"y":34,"x":411,"var":"score","name":"score"},"child":[{"type":"Clip","props":{"var":"score_1","skin":"comp/ui/clip_fnt_score.png","name":"score_1","clipX":10,"clipWidth":36.7}},{"type":"Clip","props":{"x":37,"var":"score_2","skin":"comp/ui/clip_fnt_score.png","name":"score_2","clipX":10,"clipWidth":36.7}},{"type":"Clip","props":{"x":74,"var":"score_3","skin":"comp/ui/clip_fnt_score.png","name":"score_3","clipX":10,"clipWidth":36.7}}]},{"type":"Image","props":{"y":128,"x":105,"visible":false,"var":"combo","skin":"comp/ui/caCombo.png","name":"combo"}},{"type":"Clip","props":{"y":128,"x":350,"visible":false,"var":"combo_1","skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"combo_1","clipX":10}},{"type":"Clip","props":{"y":128,"x":410,"visible":false,"var":"combo_2","skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"combo_2","clipX":10}}]};}
		]);
		return GameUI;
	})(View);
var GameOverUI=(function(_super){
		function GameOverUI(){

		    this.more=null;
		    this.share=null;
		    this.again=null;
		    this.item2=null;
		    this.item3=null;
		    this.item4=null;

			GameOverUI.__super.call(this);
		}

		CLASS$(GameOverUI,'ui.GameOverUI',_super);
		var __proto__=GameOverUI.prototype;
		__proto__.createChildren=function(){

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(GameOverUI.uiView);
		}

		STATICATTR$(GameOverUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"width":600,"skin":"comp/ui/img_bg1.png","height":966}},{"type":"Image","props":{"y":96,"x":66,"skin":"/Public/game/basketball/bin/comp/ui/img_bg2.png"}},{"type":"Button","props":{"y":666,"x":156,"width":288,"var":"more","stateNum":"2","skin":"comp/ui/btn_more.fw.png","name":"more","height":70}},{"type":"Button","props":{"y":496,"x":156,"width":288,"var":"share","stateNum":"2","skin":"comp/ui/btn_share.fw.png","name":"share","height":70}},{"type":"Button","props":{"y":583,"x":156,"width":288,"var":"again","stateNum":"2","skin":"comp/ui/btn_restart.fw.png","name":"again","height":70}},{"type":"Clip","props":{"y":382,"x":151,"skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"item0","clipX":10}},{"type":"Clip","props":{"y":382,"x":211,"skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"item1","clipX":10}},{"type":"Clip","props":{"y":382,"x":271,"var":"item2","skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"item2","clipX":10}},{"type":"Clip","props":{"y":382,"x":331,"var":"item3","skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"item3","clipX":10}},{"type":"Clip","props":{"y":382,"x":391,"var":"item4","skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"item4","clipX":10}}]};}
		]);
		return GameOverUI;
	})(View);
var GameStartUI=(function(_super){
		function GameStartUI(){

		    this.btn_start=null;

			GameStartUI.__super.call(this);
		}

		CLASS$(GameStartUI,'ui.GameStartUI',_super);
		var __proto__=GameStartUI.prototype;
		__proto__.createChildren=function(){

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(GameStartUI.uiView);
		}

		STATICATTR$(GameStartUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"/Public/game/basketball/bin/comp/ui/startBg.png"}},{"type":"Button","props":{"y":786,"x":183,"width":288,"var":"btn_start","stateNum":"2","skin":"comp/ui/btn_start.fw.png","name":"btn_start","height":131}}]};}
		]);
		return GameStartUI;
	})(View);
var Hoop_1UI=(function(_super){
		function Hoop_1UI(){

		    this.hoop=null;
		    this.top_frame=null;
		    this.bottom_frame_hit=null;
		    this.bottom_frame_normal=null;
		    this.light=null;

			Hoop_1UI.__super.call(this);
		}

		CLASS$(Hoop_1UI,'ui.Hoop_1UI',_super);
		var __proto__=Hoop_1UI.prototype;
		__proto__.createChildren=function(){

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(Hoop_1UI.uiView);
		}

		STATICATTR$(Hoop_1UI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"var":"hoop","skin":"comp/ui/basket_bg.png","name":"hoop"}},{"type":"Sprite","props":{"y":112,"x":13,"width":150,"var":"top_frame","name":"top_frame","height":55},"child":[{"type":"Image","props":{"y":0,"x":0,"width":149,"skin":"comp/ui/big1.png","height":27}},{"type":"Image","props":{"y":39,"x":75,"width":149,"skin":"comp/ui/big1.png","rotation":180,"pivotY":14.432989690721627,"pivotX":74.22680412371133,"height":27}}]},{"type":"Image","props":{"y":138,"x":12,"width":149,"visible":false,"var":"bottom_frame_hit","skin":"comp/ui/big3.png","name":"bottom_frame_hit","height":105}},{"type":"Image","props":{"y":137,"x":12,"width":149,"var":"bottom_frame_normal","skin":"comp/ui/big2.png","name":"bottom_frame_normal","height":88}},{"type":"Image","props":{"y":58,"x":28,"width":127,"visible":false,"var":"light","skin":"comp/ui/light.png","name":"light","height":128}}]};}
		]);
		return Hoop_1UI;
	})(View);
var Hoop_2UI=(function(_super){
		function Hoop_2UI(){

		    this.hoop=null;
		    this.no=null;
		    this.top_frame=null;
		    this.light=null;
		    this.bottom_frame_hit=null;
		    this.bottom_frame_normal=null;
		    this.miss=null;

			Hoop_2UI.__super.call(this);
		}

		CLASS$(Hoop_2UI,'ui.Hoop_2UI',_super);
		var __proto__=Hoop_2UI.prototype;
		__proto__.createChildren=function(){

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(Hoop_2UI.uiView);
		}

		STATICATTR$(Hoop_2UI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"var":"hoop","skin":"comp/ui/basket_bg.png","name":"hoop"}},{"type":"Image","props":{"y":47,"x":61,"width":54,"var":"no","skin":"comp/ui/no.png","name":"no","height":48}},{"type":"Sprite","props":{"y":113,"x":13,"width":150,"var":"top_frame","name":"top_frame","height":55},"child":[{"type":"Image","props":{"y":41,"x":73,"width":140,"skin":"comp/ui/red1.png","rotation":180,"pivotY":12.658227848101262,"pivotX":70.8860759493671,"height":28}},{"type":"Image","props":{"y":-2,"x":4,"skin":"comp/ui/red1.png"}}]},{"type":"Image","props":{"y":62,"x":31,"width":110,"visible":false,"var":"light","skin":"comp/ui/light.png","name":"light","height":111}},{"type":"Image","props":{"y":142,"x":18,"visible":false,"var":"bottom_frame_hit","skin":"comp/ui/red3.png","name":"bottom_frame_hit"}},{"type":"Image","props":{"y":139,"x":16,"var":"bottom_frame_normal","skin":"comp/ui/red2.png","name":"bottom_frame_normal"}},{"type":"Image","props":{"y":25,"x":-84,"width":134,"visible":false,"var":"miss","skin":"comp/ui/miss.png","name":"miss","height":49}}]};}
		]);
		return Hoop_2UI;
	})(View);
var Hoop_3UI=(function(_super){
		function Hoop_3UI(){

		    this.hoop=null;
		    this.top_frame=null;
		    this.light=null;
		    this.bottom_frame_hit=null;
		    this.bottom_frame_normal=null;
		    this.miss=null;

			Hoop_3UI.__super.call(this);
		}

		CLASS$(Hoop_3UI,'ui.Hoop_3UI',_super);
		var __proto__=Hoop_3UI.prototype;
		__proto__.createChildren=function(){

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(Hoop_3UI.uiView);
		}

		STATICATTR$(Hoop_3UI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"var":"hoop","skin":"comp/ui/basket_bg.png","name":"hoop"}},{"type":"Sprite","props":{"y":105,"x":13,"width":150,"var":"top_frame","name":"top_frame","height":55},"child":[{"type":"Image","props":{"y":2,"x":4,"skin":"comp/ui/green1.png"}},{"type":"Image","props":{"y":40,"x":74,"width":140,"skin":"comp/ui/green1.png","rotation":180,"pivotY":12.834224598930462,"pivotX":70.05347593582883,"height":28}}]},{"type":"Image","props":{"y":23,"x":35,"skin":"comp/ui/double1.png"}},{"type":"Image","props":{"y":50,"x":28,"width":127,"visible":false,"var":"light","skin":"comp/ui/light.png","name":"light","height":128}},{"type":"Image","props":{"y":138,"x":18,"width":138,"visible":false,"var":"bottom_frame_hit","skin":"comp/ui/green3.png","pivotY":1.2195121951219505,"name":"bottom_frame_hit","height":88}},{"type":"Image","props":{"y":132,"x":18,"var":"bottom_frame_normal","skin":"comp/ui/green2.png","name":"bottom_frame_normal"}},{"type":"Image","props":{"y":-2,"x":-201,"width":227,"visible":false,"var":"miss","skin":"comp/ui/double.png","name":"miss","height":50}}]};}
		]);
		return Hoop_3UI;
	})(View);
var Hoop_4UI=(function(_super){
		function Hoop_4UI(){

		    this.hoop=null;
		    this.top_frame=null;
		    this.light=null;
		    this.bottom_frame_hit=null;
		    this.bottom_frame_normal=null;

			Hoop_4UI.__super.call(this);
		}

		CLASS$(Hoop_4UI,'ui.Hoop_4UI',_super);
		var __proto__=Hoop_4UI.prototype;
		__proto__.createChildren=function(){

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(Hoop_4UI.uiView);
		}

		STATICATTR$(Hoop_4UI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"var":"hoop","skin":"comp/ui/basket_bg.png","name":"hoop"}},{"type":"Sprite","props":{"y":113,"x":13,"width":150,"var":"top_frame","name":"top_frame","height":55},"child":[{"type":"Image","props":{"y":-1,"x":4,"skin":"comp/ui/yellow1.png"}},{"type":"Image","props":{"y":35,"x":75,"width":139,"skin":"comp/ui/yellow1.png","rotation":180,"pivotY":14.569536423841072,"pivotX":68.21192052980132,"height":29}}]},{"type":"Image","props":{"y":50,"x":28,"width":127,"visible":false,"var":"light","skin":"comp/ui/light.png","name":"light","height":128}},{"type":"Image","props":{"y":137,"x":16,"visible":false,"var":"bottom_frame_hit","skin":"comp/ui/yellow3.png","name":"bottom_frame_hit"}},{"type":"Image","props":{"y":138,"x":17,"var":"bottom_frame_normal","skin":"comp/ui/yellow2.png","name":"bottom_frame_normal"}}]};}
		]);
		return Hoop_4UI;
	})(View);
