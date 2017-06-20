var CLASS$=Laya.class;
var STATICATTR$=Laya.static;
var View=laya.ui.View;
var Dialog=laya.ui.Dialog;
var GameUI=(function(_super){
		function GameUI(){
			
		    this.score=null;
		    this.score_1=null;
		    this.score_2=null;
		    this.score_3=null;
		    this.combo=null;
		    this.combo_1=null;
		    this.combo_2=null;
		    this.btn_Shoot=null;
		    this.btn_Shoot_ball=null;
		    this.btn_Shoot_biu=null;
		    this.upBack=null;
		    this.inloading=null;
		    this.ttf=null;

			GameUI.__super.call(this);
		}

		CLASS$(GameUI,'ui.GameUI',_super);
		var __proto__=GameUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("Text",laya.display.Text);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(GameUI.uiView);
		}

		STATICATTR$(GameUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"/Public/game/basketball/bin/comp/ui/img_bg.png"}},{"type":"Box","props":{"y":34,"x":450,"var":"score","name":"score"},"child":[{"type":"Clip","props":{"var":"score_1","skin":"comp/ui/clip_fnt_score.png","name":"score_1","clipX":10,"clipWidth":36.5}},{"type":"Clip","props":{"x":37,"var":"score_2","skin":"comp/ui/clip_fnt_score.png","name":"score_2","clipX":10,"clipWidth":36.5}},{"type":"Clip","props":{"x":74,"var":"score_3","skin":"comp/ui/clip_fnt_score.png","name":"score_3","clipX":10,"clipWidth":36.5}}]},{"type":"Image","props":{"y":128,"x":105,"visible":false,"var":"combo","skin":"comp/ui/caCombo.png","name":"combo"}},{"type":"Clip","props":{"y":128,"x":350,"visible":false,"var":"combo_1","skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"combo_1","clipX":10}},{"type":"Clip","props":{"y":128,"x":410,"visible":false,"var":"combo_2","skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"combo_2","clipX":10}},{"type":"Sprite","props":{"y":33,"x":13,"width":348,"var":"btn_Shoot","name":"btn_Shoot","height":940},"child":[{"type":"Image","props":{"y":822,"x":294,"width":91,"var":"btn_Shoot_ball","skin":"comp/ui/ball.png","pivotY":45.5,"pivotX":45.5,"height":91}},{"type":"Image","props":{"y":832,"x":238,"var":"btn_Shoot_biu","skin":"comp/ui/biu2.png"}}]},{"type":"Button","props":{"y":853,"x":481,"width":91,"var":"upBack","stateNum":"1","skin":"comp/ui/625156081182945207.png","labelSize":30,"labelColors":"#ffffff","labelBold":true,"labelAlign":"center","height":85}},{"type":"Image","props":{"y":491,"x":539,"skin":"comp/ui/676083417634433453.png"}},{"type":"Image","props":{"y":653,"x":555,"width":38,"var":"inloading","skin":"comp/ui/769547118814017193.png","sizeGrid":"13,21,18,17","rotation":180,"pivotY":165.82278481012668,"pivotX":21.518987341772117,"height":30}},{"type":"Text","props":{"y":28,"x":131,"width":117,"var":"ttf","valign":"middle","name":"ttf","height":45,"align":"center"}}]};}
		]);
		return GameUI;
	})(View);
var GameOverUI=(function(_super){
		function GameOverUI(){
			
		    this.more=null;
		    this.share=null;
		    this.again=null;
		    this.item0=null;
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
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"width":600,"skin":"comp/ui/img_bg1.png","height":966}},{"type":"Image","props":{"y":96,"x":66,"skin":"/Public/game/basketball/bin/comp/ui/img_bg2.png"}},{"type":"Button","props":{"y":666,"x":156,"width":288,"var":"more","stateNum":"2","skin":"comp/ui/btn_more.fw.png","name":"more","height":70}},{"type":"Button","props":{"y":496,"x":156,"width":288,"var":"share","stateNum":"2","skin":"comp/ui/btn_share.fw.png","name":"share","height":70}},{"type":"Button","props":{"y":583,"x":156,"width":288,"var":"again","stateNum":"2","skin":"comp/ui/btn_restart.fw.png","name":"again","height":70}},{"type":"Clip","props":{"y":382,"x":151,"var":"item0","skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"item0","clipX":10}},{"type":"Clip","props":{"y":382,"x":211,"skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"item1","clipX":10}},{"type":"Clip","props":{"y":382,"x":271,"var":"item2","skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"item2","clipX":10}},{"type":"Clip","props":{"y":382,"x":331,"var":"item3","skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"item3","clipX":10}},{"type":"Clip","props":{"y":382,"x":391,"var":"item4","skin":"/Public/game/basketball/bin/comp/ui/clip_fnt_double.png","name":"item4","clipX":10}}]};}
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
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"comp/ui/startBg.png"}},{"type":"Button","props":{"y":786,"x":183,"width":288,"var":"btn_start","stateNum":"2","skin":"comp/ui/btn_start.fw.png","name":"btn_start","height":131}}]};}
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
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Sprite","props":{"y":65,"x":43,"width":171,"scaleY":0.6,"scaleX":0.6,"pivotY":124.99999999999999,"pivotX":84.61538461538461,"height":243},"child":[{"type":"Image","props":{"y":0,"x":0,"var":"hoop","skin":"comp/ui/basket_bg.png","name":"hoop"}},{"type":"Sprite","props":{"y":112,"x":13,"width":150,"var":"top_frame","name":"top_frame","height":55},"child":[{"type":"Image","props":{"y":0,"x":0,"width":149,"skin":"comp/ui/big1.png","height":27}},{"type":"Image","props":{"y":39,"x":75,"width":149,"skin":"comp/ui/big1.png","rotation":180,"pivotY":14.432989690721627,"pivotX":74.22680412371133,"height":27}}]},{"type":"Image","props":{"y":138,"x":13,"width":149,"visible":false,"var":"bottom_frame_hit","skin":"comp/ui/big3.png","name":"bottom_frame_hit","height":105}},{"type":"Image","props":{"y":137,"x":13,"width":147,"var":"bottom_frame_normal","skin":"comp/ui/big2.png","name":"bottom_frame_normal","height":86}},{"type":"Image","props":{"y":58,"x":28,"width":127,"visible":false,"var":"light","skin":"comp/ui/light.png","name":"light","height":128}}]},{"type":"Image","props":{"y":12,"x":2,"width":82,"skin":"comp/ui/10do.png","height":49}}]};}
		]);
		return Hoop_1UI;
	})(View);
var Hoop_2UI=(function(_super){
		function Hoop_2UI(){
			
		    this.hoop=null;
		    this.top_frame=null;
		    this.light=null;
		    this.bottom_frame_hit=null;
		    this.bottom_frame_normal=null;

			Hoop_2UI.__super.call(this);
		}

		CLASS$(Hoop_2UI,'ui.Hoop_2UI',_super);
		var __proto__=Hoop_2UI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(Hoop_2UI.uiView);
		}

		STATICATTR$(Hoop_2UI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Sprite","props":{"scaleY":0.6,"scaleX":0.6},"child":[{"type":"Image","props":{"y":0,"x":0,"var":"hoop","skin":"comp/ui/basket_bg.png","name":"hoop"}},{"type":"Sprite","props":{"y":113,"x":13,"width":150,"var":"top_frame","name":"top_frame","height":55},"child":[{"type":"Image","props":{"y":38,"x":73,"width":140,"skin":"comp/ui/red1.png","rotation":180,"pivotY":12.658227848101262,"pivotX":70.8860759493671,"height":28}},{"type":"Image","props":{"y":-2,"x":4,"skin":"comp/ui/red1.png"}}]},{"type":"Image","props":{"y":62,"x":31,"width":110,"visible":false,"var":"light","skin":"comp/ui/light.png","name":"light","height":111}},{"type":"Image","props":{"y":140,"x":18,"visible":false,"var":"bottom_frame_hit","skin":"comp/ui/red3.png","name":"bottom_frame_hit"}},{"type":"Image","props":{"y":139,"x":18,"var":"bottom_frame_normal","skin":"comp/ui/red2.png","name":"bottom_frame_normal"}}]},{"type":"Image","props":{"y":13,"x":10,"width":82,"skin":"comp/ui/15do.png","height":49}}]};}
		]);
		return Hoop_2UI;
	})(View);
var Hoop_3UI=(function(_super){
		function Hoop_3UI(){
			
		    this.hoop=null;
		    this.top_frame=null;
		    this.bottom_frame_hit=null;
		    this.bottom_frame_normal=null;

			Hoop_3UI.__super.call(this);
		}

		CLASS$(Hoop_3UI,'ui.Hoop_3UI',_super);
		var __proto__=Hoop_3UI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(Hoop_3UI.uiView);
		}

		STATICATTR$(Hoop_3UI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Sprite","props":{"scaleY":0.6,"scaleX":0.6},"child":[{"type":"Image","props":{"y":0,"x":0,"var":"hoop","skin":"comp/ui/basket_bg.png","name":"hoop"}},{"type":"Sprite","props":{"y":105,"x":13,"width":150,"var":"top_frame","name":"top_frame","height":55},"child":[{"type":"Image","props":{"y":2,"x":4,"skin":"comp/ui/green1.png"}},{"type":"Image","props":{"y":39,"x":74,"width":140,"skin":"comp/ui/green1.png","rotation":180,"pivotY":12.834224598930462,"pivotX":70.05347593582883,"height":28}}]},{"type":"Image","props":{"y":135,"x":17,"width":141,"visible":false,"var":"bottom_frame_hit","skin":"comp/ui/green3.png","pivotY":1.2195121951219505,"name":"bottom_frame_hit","height":88}},{"type":"Image","props":{"y":132,"x":18,"var":"bottom_frame_normal","skin":"comp/ui/green2.png","name":"bottom_frame_normal"}},{"type":"Image","props":{"y":24,"x":8,"skin":"comp/ui/20do.png"}}]}]};}
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
		['uiView',function(){return this.uiView={"type":"View","props":{"width":600,"height":966},"child":[{"type":"Sprite","props":{"scaleY":0.6,"scaleX":0.6},"child":[{"type":"Image","props":{"y":0,"x":0,"var":"hoop","skin":"comp/ui/basket_bg.png","name":"hoop"}},{"type":"Sprite","props":{"y":113,"x":13,"width":150,"var":"top_frame","name":"top_frame","height":55},"child":[{"type":"Image","props":{"y":38,"x":73,"width":140,"skin":"comp/ui/red1.png","rotation":180,"pivotY":12.658227848101262,"pivotX":70.8860759493671,"height":28}},{"type":"Image","props":{"y":-2,"x":4,"skin":"comp/ui/red1.png"}}]},{"type":"Image","props":{"y":62,"x":31,"width":110,"visible":false,"var":"light","skin":"comp/ui/light.png","name":"light","height":111}},{"type":"Image","props":{"y":140,"x":18,"visible":false,"var":"bottom_frame_hit","skin":"comp/ui/red3.png","name":"bottom_frame_hit"}},{"type":"Image","props":{"y":139,"x":18,"var":"bottom_frame_normal","skin":"comp/ui/red2.png","name":"bottom_frame_normal"}}]},{"type":"Image","props":{"y":13,"x":10,"width":82,"skin":"comp/ui/30do.png","height":49}}]};}
		]);
		return Hoop_4UI;
	})(View);
var rechangesUI=(function(_super){
		function rechangesUI(){
			
		    this.btn_100=null;
		    this.btn_1000=null;
		    this.btn_5=null;
		    this.btn_10=null;
		    this.btn_20=null;
		    this.closeDialog=null;

			rechangesUI.__super.call(this);
		}

		CLASS$(rechangesUI,'ui.rechangesUI',_super);
		var __proto__=rechangesUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(rechangesUI.uiView);
		}

		STATICATTR$(rechangesUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":600,"height":966},"child":[{"type":"Image","props":{"y":157,"x":86,"width":473,"skin":"comp/rechange/216026477095902766.png","height":716}},{"type":"Image","props":{"y":331,"x":119,"width":96,"skin":"comp/rechange/827603792205340250.png","height":112}},{"type":"Image","props":{"y":317,"x":260,"width":124,"skin":"comp/rechange/655459171451697932.png","height":140}},{"type":"Image","props":{"y":317,"x":409,"width":132,"skin":"comp/rechange/625019508528695890.png","height":142}},{"type":"Image","props":{"y":746,"x":155,"width":113,"var":"btn_100","skin":"comp/rechange/240868246904839377.png","name":"btn_100","height":36}},{"type":"Image","props":{"y":745,"x":376,"width":113,"var":"btn_1000","skin":"comp/rechange/240868246904839377.png","name":"btn_1000","height":36}},{"type":"Image","props":{"y":466,"x":118,"width":113,"var":"btn_5","skin":"comp/rechange/240868246904839377.png","name":"btn_5","height":36}},{"type":"Image","props":{"y":466,"x":262,"width":113,"var":"btn_10","skin":"comp/rechange/240868246904839377.png","name":"btn_10","height":36}},{"type":"Image","props":{"y":563,"x":123,"skin":"comp/rechange/361784790948656569.png"}},{"type":"Image","props":{"y":553,"x":338,"skin":"comp/rechange/152714223423133639.png"}},{"type":"Image","props":{"y":466,"x":418,"width":113,"var":"btn_20","skin":"comp/rechange/240868246904839377.png","name":"btn_20","height":36}},{"type":"Image","props":{"y":180,"x":499,"width":70,"var":"closeDialog","skin":"comp/rechange/149925559955638041.png","name":"closeDialog","height":70}}]};}
		]);
		return rechangesUI;
	})(Dialog);