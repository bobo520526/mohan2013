// 随机产生南瓜，加载南瓜到舞台，南瓜走起来
(function(){
    var self;
    function aPe(){
        aPe.__super.call(this);
        this.init();

    }
    Laya.class(aPe,"aPe",laya.display.Sprite);
    var _proto=aPe.prototype;
    _proto.init=function(){
        this.addtime=0;
        this.showTime = 0;
        self = this;
        // 新建一个南瓜层
        this.pkLayer=new Laya.Sprite();
        this.addChild(this.pkLayer);
        this.apeAni();
        Laya.timer.frameLoop(1,this,this.onRun);
        this.speed=.5;
    }
//   生成
    _proto.onRun=function(){
        var s=this;
        // 节流阀
        s.addtime++;
        if(s.addtime>=Math.floor(Math.random()*70+20)){
             s.apeAni();
             s.addtime = 0;
             s.speed+=0.6;
        }
        
        for(key in s.pkLayer._childs){
           if(s.pkLayer._childs[key].mode=="run"){
            // 运动时间与速度
            s.pkLayer._childs[key].x-=0.2*s.speed + s.pkLayer._childs[key].a;

            if(s.pkLayer._childs[key].x<30){
                s.pkLayer._childs[key].mode="die";
            }
           }
           else if(s.pkLayer._childs[key].mode=="die"){
                s.pkLayer._childs[key].removeSelf();
           }
           else if(s.pkLayer._childs[key].mode=="ying"){
                s.pkLayer._childs[key].stop();
                 s.canDrag=false;
           }
        }
    }
    _proto.apeAni=function(){
        var s=this;
        var y=(Math.random()*300)+200;
        s.pumkinLayers=new Laya.Animation();
        s.pumkinLayers.width = 100;
        s.pumkinLayers.height = 146;
        s.pumkinLayers.loadAtlas(urlString+"res/atlas/puk.json");
        s.pumkinLayers.interval=120;
        s.pumkinLayers.play();
        s.pumkinLayers.mode="run";
        s.pumkinLayers.types="Open";
        s.pumkinLayers.canDrag=true;
        s.pumkinLayers.num = 1;
        s.pumkinLayers.a =20-Math.ceil(Math.random()*20);
        s.pumkinLayers.pos(600,y);
        s.pkLayer.addChild(s.pumkinLayers);
        s.pumkinLayers.on(Laya.Event.MOUSE_DOWN,s.pumkinLayers,this.dragPuk);
        s.pumkinLayers.on(Laya.Event.MOUSE_UP,s.pumkinLayers,this.onUp);
        s.pumkinLayers.on(Laya.Event.MOUSE_MOVE,s.pumkinLayers,this.onMove);
    }
    _proto.dragPuk=function(e){
        self.moveX = e.stageX;
        self.moveX = e.stageY;
        this.mode="ying";
        if(this.canDrag==true){
            this.startDrag();
            this.stop();    
        }
    }
     _proto.onUp = function(){
        var s=this;
        if((s.y+s.height)<=700){
                s.play(1,true);
                s.mode="run";
                s.canDrag=true;
            }
        else {
                s.mode="ying";
                s.canDrag=false;
                s.types="win"; 
        }
     }
     _proto.onMove = function(e){
         var s = this;
         self.moveX = e.stageX ; 
         self.moveY = e.stageY ;
         if(self.moveY>=600){
            if(self.moveX<=10 || self.moveX>=590){
                 s.mode="ying";
                s.canDrag=false;
                s.types="win"; 
            }
         }
          if(self.moveY>=920){
                 s.mode="ying";
                s.canDrag=false;
                s.types="win"; 
         }
         
     }
    

})();