//野怪出现

(function(){
    function beatIt(){
        beatIt.__super.call(this);
        this.init();
    }
Laya.class(beatIt,"beatIt",laya.display.Sprite);
    var _proto=beatIt.prototype;
     _proto.init=function(){
        //实例化怪层
        var s = this;
        this.guaiLayer=new Laya.Sprite();
        this.addChild(this.guaiLayer);
        this.mAniPath;
        this.mStartY =100;
        this.mFactory;
        this.mActionIndex = 0;
        this.mCurrIndex = 0;
        this.mArmature;
        this.mCurrSkinIndex = 0;
         s.startFun(); 
        setInterval(function(){
          s.startFun();  
        },4000)

    }
	_proto.startFun=function()
	{
		this.mAniPath = urlStrings+"res/spine/qw2/qwwww.sk";
		this.mFactory = new Laya.Templet();
        this.mFactory.mode = "run";
		this.mFactory.on(Laya.Event.COMPLETE, this, this.parseComplete);
		this.mFactory.on(Laya.Event.ERROR, this, this.onError);
		this.mFactory.loadAni(this.mAniPath);
      
	}
    _proto.onError=function()
	{
		trace("error");
	}	
  
    _proto.parseComplete=function() {
            var R=Math.ceil(Math.random()*2)
            for(var i=0;i<R;i++){
            //创建模式为1，可以启用换装
            this.mArmature = this.mFactory.buildArmature(1);
            // 坐标随机
             // X轴随机坐标
            this.mArmature.x =Math.floor(Math.random()*535+45);
            this.mArmature.y =-Math.floor(Math.random()*10);
            this.mArmature.mode = "run";
            this.mArmature.scale(1, 1);
            this.mArmature.hp = 10;
             this.mArmature.add = 200;
            this.guaiLayer.addChild(this.mArmature);
            this.mArmature.on(Laya.Event.STOPPED, this, this.completeHandler);
            this.plays(0,true);
         
         }
} 
    
	_proto.completeHandler=function(){
        this.plays(0,true);
    }
	
	_proto.plays=function(index,type)
	{
		this.mCurrIndex=index;
		this.mArmature.play(this.mCurrIndex,type);
	}

})()