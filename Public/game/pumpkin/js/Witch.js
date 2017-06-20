//女巫出现，条件：当南瓜的x轴为x1时，出现女巫

(function(){
    function Witch(){
        Witch.__super.call(this);
        this.init();
    }
    Laya.class(Witch,"Witch",laya.display.Sprite);
    var _proto=Witch.prototype;
    _proto.init=function(){
        this.addtime=0;
        // 新建一个女巫
        this.witPlayer=new Laya.Sprite();
        this.addChild(this.witPlayer);
        this.ApePath=urlString+"comp/badwoman.png";
    }

    _proto.witAppear=function(_x,_y){
        this.wits=new Laya.Sprite();
        this.witPlayer.addChild(this.wits);
        this.wits.loadImage(this.ApePath);
        this.wits.x=_x;
        this.wits.y=_y;
        this.wits.pos(_x,_y);
        Laya.Tween.to(this.wits, {x:_x+10,y:_y}, 500, null, Laya.Handler.create(this.wits, function() {
			this.removeSelf();
		}))
    }
  
})();